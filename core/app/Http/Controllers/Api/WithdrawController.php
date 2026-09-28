<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawController extends Controller {
    public function withdrawMethod() {
        $currencies = Currency::with('userDetailsData')->enabled()->where('available_for_buy', Status::YES)->get();
        $notify[]   = 'Withdraw Money';

        return responseSuccess('withdraw_methods', $notify, [
            'withdrawCurrencies' => $currencies,
            'imagePath'          => getFilePath('currency'),
        ]);
    }

    public function withdrawStore(Request $request) {
        $validator = Validator::make($request->all(), [
            'currency_id' => 'required',
            'amount'      => 'required|numeric|gte:0',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors());
        }

        $user = auth()->user();

        if ($request->amount > $user->balance) {
            $notify[] = 'You have not enough balance';
            return responseError('validation_error', $notify);
        }

        $currency = Currency::enabled()->availableForSell()->where('id', $request->currency_id)->first();
        if (!$currency) {
            $notify[] = 'Invalid currency';
            return responseError('validation_error', $notify);
        }
        $formData = @$currency->userDetailsData->form_data ?? null;

        $formProcessor = new FormProcessor();
        if ($formData) {
            $validationRule = $formProcessor->valueValidation($formData);
            $validationRule = $formProcessor->valueValidation($formData);
            $validator      = Validator::make($request->all(), $validationRule);
            if ($validator->fails()) {
                return responseError('validation_error', $validator->errors());
            }
        }

        $formValue = $formProcessor->processFormData($request, $formData);

        if ($request->amount < ($currency->minimum_limit_for_sell * $currency->sell_at)) {
            $notify[] = 'Please follow the minimum limit';
            return responseError('validation_error', $notify);
        }

        if ($request->amount > ($currency->maximum_limit_for_sell * $currency->sell_at)) {
            $notify[] = 'Please follow the maximum limit';
            return responseError('validation_error', $notify);
        }

        $getAmount = $request->amount / $currency->sell_at;
        $charge    = $currency->fixed_charge_for_sell + ($getAmount * $currency->percent_charge_for_sell / 100);

        $withdraw                       = new Withdrawal();
        $withdraw->method_id            = $currency->id;
        $withdraw->user_id              = $user->id;
        $withdraw->amount               = $request->amount;
        $withdraw->currency             = gs('cur_text');
        $withdraw->rate                 = $currency->sell_at;
        $withdraw->charge               = $charge;
        $withdraw->final_amount         = $getAmount;
        $withdraw->after_charge         = $getAmount - $charge;
        $withdraw->trx                  = getTrx();
        $withdraw->status               = Status::WITHDRAW_PENDING;
        $withdraw->withdraw_information = $formValue;
        $withdraw->save();

        $user->balance -= $withdraw->amount;
        $user->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id);
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name'          => $withdraw->method->name,
            'method_currency'      => $withdraw->method->cur_sym,
            'method_amount'        => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount'               => showAmount($withdraw->amount, currencyFormat: false),
            'charge'               => showAmount($withdraw->charge, currencyFormat: false),
            'rate'                 => showAmount($withdraw->rate, currencyFormat: false),
            'trx'                  => $withdraw->trx,
            'post_balance'         => showAmount($user->balance, currencyFormat: false),
            'balance_after_charge' => showAmount($withdraw->after_charge, currencyFormat: false),
        ]);

        $notify[] = 'Please wait for admin approval';
        return responseSuccess('withdraw', $notify);
    }

    public function withdrawLog(Request $request) {
        $withdraws = Withdrawal::where('user_id', auth()->id());
        if ($request->search) {
            $withdraws = $withdraws->where('trx', $request->search);
        }
        $withdraws = $withdraws->where('status', '!=', Status::PAYMENT_INITIATE)->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[]  = 'Withdrawals';

        return responseSuccess('withdrawals', $notify, [
            'withdrawals' => $withdraws,
        ]);
    }
}
