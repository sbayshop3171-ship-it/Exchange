<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function withdrawMoney()
    {
        $pageTitle  = 'Withdraw Money';
        $user       = auth()->user();
        $currencies = Currency::enabled()->where('available_for_buy', Status::YES)->get();
        return view('Template::user.withdraw.methods', compact('pageTitle', 'currencies', 'user'));
    }

    public function withdrawStore(Request $request)
    {
        $request->validate([
            'currency_id' => 'required',
            'amount'      => 'required|numeric|gte:0',
        ]);

        $user = auth()->user();

        if ($request->amount > $user->balance) {
            $notify[] = ['error', 'You have not enough balance'];
            return back()->withNotify($notify);
        }

        $currency = Currency::enabled()->availableForSell()->where('id', $request->currency_id)->firstOrFail();
        $formData = @$currency->userDetailsData->form_data ?? null;

        $formProcessor = new FormProcessor();
        if ($formData) {
            $validationRule = $formProcessor->valueValidation($formData);
            $request->validate($validationRule);
        }
        $formValue = $formProcessor->processFormData($request, $formData);

        if ($request->amount < ($currency->minimum_limit_for_sell * $currency->sell_at)) {
            $notify[] = ['error', 'Please follow the minimum limit'];
            return back()->withNotify($notify);
        }

        if ($request->amount > ($currency->maximum_limit_for_sell * $currency->sell_at)) {
            $notify[] = ['error', 'Please follow the maximum limit'];
            return back()->withNotify($notify);
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

        $notify[] = ['success', 'Please wait for admin approval'];
        return to_route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdrawal Log";
        $withdraws = Withdrawal::where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE);
        if ($request->search) {
            $withdraws = $withdraws->where('trx', $request->search);
        }
        $withdraws = $withdraws->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.withdraw.log', compact('pageTitle', 'withdraws'));
    }

    public function currencyUserData($id)
    {
        $currency = Currency::enabled()->where('available_for_buy', Status::YES)->where('id', $id)->first();
        if (!$currency) {
            return response()->json([
                'success' => false,
                'message' => "Currency not found"
            ]);
        }
        $formData = @$currency->userDetailsData->form_data ?? null;
        $html     = $formData ? view('components.viser-form', compact('formData'))->render() : '';
        return response()->json([
            'success' => true,
            'html'    => $html
        ]);
    }
}
