<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Exchange;
use App\Models\Form;
use App\Models\GatewayCurrency;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExchangeController extends Controller {
    protected $sendCurrencyRelation     = 'sendCurrency';
    protected $receivedCurrencyRelation = 'receivedCurrency';
    protected $userRelation             = 'user';

    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
            'sending_amount'     => 'required|numeric|gte:0',
            'sending_currency'   => 'required|integer',
            'receiving_currency' => 'required|integer|different:sending_currency',
        ]);

        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors());
        }

        $sendCurrency = Currency::enabled()->availableForSell()->find($request->sending_currency);
        if (!$sendCurrency) {
            $notify[] = 'Sending currency not found';
            return responseSuccess('validation_error', $notify);
        }

        $receiveCurrency = Currency::enabled()->availableForBuy()->find($request->receiving_currency);
        if (!$receiveCurrency) {
            $notify[] = 'Receiving currency not found';
            return responseError('validation_error', $validator->errors());
        }

        $sendAmount = $request->sending_amount;
        try {
            $sendingPercentCharge   = $sendAmount / 100 * $sendCurrency->percent_charge_for_buy;
            $sendingFixedCharge     = $sendCurrency->fixed_charge_for_buy;
            $totalSendingCharge     = $sendingFixedCharge + $sendingPercentCharge;
            $receiveAmount          = $sendCurrency->buy_at / $receiveCurrency->sell_at * $sendAmount;
            $receivingPercentCharge = $receiveAmount / 100 * $receiveCurrency->percent_charge_for_sell;
            $receivingFixedCharge   = $receiveCurrency->fixed_charge_for_sell;
            $totalReceivingCharge   = $receivingFixedCharge + $receivingPercentCharge;
            $totalReceivedAmount    = $receiveAmount - $totalReceivingCharge;
        } catch (Exception $ex) {
            $notify[] = 'Something went wrong with the exchange processing';
            return responseError('exception_found', $notify);
        }

        if ($sendAmount < $sendCurrency->minimum_limit_for_buy) {
            $notify[] = 'Minimum sending amount ' . number_format($sendCurrency->minimum_limit_for_buy, $sendCurrency->show_number_after_decimal) . ' ' . $sendCurrency->cur_sym;
            return responseError('validation_error', $notify);
        }

        if ($sendAmount > $sendCurrency->maximum_limit_for_buy) {
            $notify = 'Maximum sending amount ' . number_format($sendCurrency->maximum_limit_for_buy, $sendCurrency->show_number_after_decimal) . ' ' . $sendCurrency->cur_sym;
            return responseError('validation_error', $notify);
        }

        if ($receiveAmount < $receiveCurrency->minimum_limit_for_sell) {
            $notify = 'Minimum received amount ' . number_format($receiveCurrency->minimum_limit_for_sell, $receiveCurrency->show_number_after_decimal) . ' ' . $receiveCurrency->cur_sym;
            return responseError('validation_error', $notify);
        }

        if ($receiveAmount > $receiveCurrency->maximum_limit_for_sell) {
            $notify = 'Maximum received amount ' . number_format($receiveCurrency->maximum_limit_for_sell, $receiveCurrency->show_number_after_decimal) . ' ' . $receiveCurrency->cur_sym;
            return responseError('validation_error', $notify);
        }

        if ($totalReceivedAmount > $receiveCurrency->reserve) {
            $notify = 'Sorry, our reserve limit exceeded';
            return responseError('validation_error', $notify);
        }

        if ($totalReceivedAmount <= 0) {
            $notify[] = 'Negative amount is not acceptable';
            return responseError('validation_error', $notify);
        }

        $charge = [
            'sending_charge'   => [
                'fixed_charge'   => $sendingFixedCharge,
                'percent_charge' => $sendCurrency->percent_charge_for_buy,
                'percent_amount' => $sendingPercentCharge,
                'total_charge'   => $totalSendingCharge,
            ],
            'receiving_charge' => [
                'fixed_charge'   => $receivingFixedCharge,
                'percent_charge' => $receiveCurrency->percent_charge_for_sell,
                'percent_amount' => $receivingPercentCharge,
                'total_charge'   => $totalReceivingCharge,
            ],
        ];

        $exchange                      = new Exchange();
        $exchange->user_id             = auth()->id();
        $exchange->send_currency_id    = $sendCurrency->id;
        $exchange->receive_currency_id = $receiveCurrency->id;
        $exchange->sending_amount      = $sendAmount;
        $exchange->sending_charge      = $totalSendingCharge;
        $exchange->receiving_amount    = $receiveAmount;
        $exchange->receiving_charge    = $totalReceivingCharge;
        $exchange->sell_rate           = $receiveCurrency->sell_at;
        $exchange->buy_rate            = $sendCurrency->buy_at;
        $exchange->exchange_id         = getTrx();
        $exchange->charge              = $charge;
        if (gs('exchange_auto_cancel')) {
            $exchange->expired_at = now()->addMinutes(gs('exchange_auto_cancel_time'));
        }
        $exchange->save();

        $notify[] = 'Please provide required data for the confirm exchange';
        return responseSuccess('exchange_created', $notify, [
            'exchange' => $exchange,
        ]);
    }

    public function preview($id) {
        $exchange = Exchange::where('status', Status::EXCHANGE_INITIAL)
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->select('id', 'user_id', 'send_currency_id', 'receive_currency_id', 'sending_amount', 'receiving_amount', 'sending_charge', 'receiving_charge', 'charge', 'exchange_id', 'status')
            ->with('sendCurrency:id,name,image,cur_sym,show_number_after_decimal', 'receivedCurrency:id,name,image,cur_sym,user_detail_form_id,show_number_after_decimal')
            ->first();

        if (!$exchange) {
            $notify = 'Exchange not found';
            return responseError('exchange_preview', $notify);
        }

        $currencyImagePath = asset(getFilePath('currency'));

        $notify[] = 'Exchange preview';
        return responseSuccess('exchange_preview', $notify, [
            'exchange'            => $exchange,
            'required_data'       => @$exchange->receivedCurrency->userDetailsData,
            'currency_image_path' => $currencyImagePath,
        ]);
    }

    public function confirm(Request $request, $id) {
        $exchange = Exchange::where('status', Status::EXCHANGE_INITIAL)->where('id', $id)->where('user_id', auth()->id())->first();

        if (!$exchange) {
            $notify = 'Exchange not found';
            return responseError('exchange_confirm', $notify);
        }

        $validation = [
            'wallet_id' => 'required',
        ];

        $userRequiredData = @$exchange->receivedCurrency->userDetailsData->form_data ?? [];
        $formProcessor    = new FormProcessor();
        $validationRule   = $formProcessor->valueValidation($userRequiredData);
        $validationRule   = array_merge($validationRule, $validation);
        $validator        = Validator::make($request->all(), $validationRule);
        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors());
        }

        $userData            = $formProcessor->processFormData($request, $userRequiredData);
        $exchange->user_data = $userData ?? null;
        $exchange->wallet_id = $request->wallet_id;
        $exchange->save();

        $notify[] = 'Please make payment for complete exchange';

        //=====automatic payment
        if ($exchange->sendCurrency->gateway_id != 0) {
            $curSymbol = $exchange->sendCurrency->cur_sym;
            $code      = $exchange->sendCurrency->gatewayCurrency->code;
            $gateway   = GatewayCurrency::where('method_code', $code)->where('currency', $curSymbol)->first();

            if (!$gateway) {
                $notify = ["Something went the wrong with exchange processing"];
                return responseError('validation_error', $notify);
            }

            $amount = $exchange->sending_amount + $exchange->sending_charge;

            $deposit                  = new Deposit();
            $deposit->from_api        = 1;
            $deposit->user_id         = auth()->id();
            $deposit->method_code     = $code;
            $deposit->method_currency = strtoupper($curSymbol);
            $deposit->amount          = $amount;
            $deposit->charge          = 0;
            $deposit->rate            = $exchange->buy_rate;
            $deposit->final_amount    = getAmount($amount);
            $deposit->btc_amount      = 0;
            $deposit->btc_wallet      = "";
            $deposit->trx             = $exchange->exchange_id;
            $deposit->try             = 0;
            $deposit->status          = 0;
            $deposit->exchange_id     = $exchange->id;
            $deposit->success_url     = urlPath('user.exchange.list');
            $deposit->failed_url      = urlPath('user.exchange.list');
            $deposit->save();

            return response()->json([
                'remark'  => 'confirm_automatic_exchange',
                'status'  => 'success',
                'message' => ['success' => $notify],
                'data'    => [
                    'is_autometic' => true,
                    'exchange'     => $exchange,
                    'redirect_url' => route('deposit.app.confirm', ['hash' => encrypt($deposit->id)]),
                ],
            ]);
        }
        return responseSuccess('confirm_automatic_exchange', $notify, [
            'is_autometic' => false,
            'exchange'     => $exchange,
        ]);
    }

    public function manual($id) {
        $exchange = Exchange::where('status', Status::EXCHANGE_INITIAL)
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->with($this->sendCurrencyRelation)
            ->first();

        if (!$exchange) {
            $notify = 'Exchange not found';
            return responseSuccess('manual_exchange', $notify);
        }

        $formData = Form::where('id', @$exchange->sendCurrency->trx_proof_form_id)->first();

        $notify[] = 'Confirm Manual Exchange';
        return responseSuccess('manual_exchange_preview', $notify, [
            'exchange'      => $exchange,
            'required_data' => $formData,
        ]);
    }
    public function manualConfirm(Request $request, $id) {
        $exchange = Exchange::where('status', Status::EXCHANGE_INITIAL)
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->with('sendCurrency')
            ->first();

        if (!$exchange) {
            $notify = 'Exchange not found';
            return responseError('manual_exchange_confirm', $notify);
        }

        $transactionProvedData = @$exchange->sendCurrency->transactionProvedData->form_data ?? [];
        $formProcessor         = new FormProcessor();
        $validationRule        = $formProcessor->valueValidation($transactionProvedData);
        $validator             = Validator::make($request->all(), $validationRule);
        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors());
        }
        $provedData = $formProcessor->processFormData($request, $transactionProvedData);

        $exchange->transaction_proof_data = $provedData ?? null;
        $exchange->status                 = Status::EXCHANGE_PENDING;
        $exchange->save();

        $comment                      = 'Send ' . getAmount($exchange->get_amount) . ' by ' . @$exchange->sendCurrency->name;
        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $exchange->user_id;
        $adminNotification->title     = $comment;
        $adminNotification->click_url = urlPath('admin.exchange.details', $exchange->id);
        $adminNotification->save();

        $notify = 'Thank you for your exchange. Admin will review your request';

        return responseSuccess('exchange_success', $notify, [
            'exchange' => $exchange,
        ]);
    }

    public function list($scope = 'list') {
        $imagePath = asset(getFilePath('currency'));
        $exchanges = Exchange::query();
        if ($scope) {
            $exchanges = $exchanges->$scope();
        }
        $exchanges = $exchanges->where('user_id', auth()->id())
            ->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation)
            ->desc()
            ->paginate(getPaginate());

        $notify[] = formateScope($scope) . ' ' . 'Exchange List';
        return responseSuccess('exchanges', $notify, [
            'exchanges'  => $exchanges,
            'image_path' => $imagePath,
        ]);
    }

    public function details($id) {
        $imagePath = asset(getFilePath('currency'));
        $exchange  = Exchange::where('user_id', auth()->id())
            ->where('user_id', auth()->id())
            ->orderBy('id', 'DESC')
            ->where('id', $id)
            ->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation)
            ->first();

        if (!$exchange) {
            $notify[] = 'Exchange not found';
            return responseError('exchange_details', $notify);
        }
        $pdfDownloadPath = route('download.exchange.pdf', ['hash' => encrypt($exchange->user_id), 'id' => $exchange->id]);

        $notify[] = 'Exchange Details';
        return responseSuccess('exchange_details', $notify, [
            'exchange'        => $exchange,
            'image_path'      => $imagePath,
            'pdfDownloadPath' => $pdfDownloadPath,
        ]);
    }

    public function all() {
        $exchanges = Exchange::
            where('user_id', auth()->id())
            ->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation, $this->userRelation)
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        $imagePath = asset(getFilePath('currency'));

        $notify[] = 'Latest Exchange List';
        return responseSuccess('exchange_details', $notify, [
            'exchanges'  => $exchanges,
            'image_path' => $imagePath,
        ]);
    }

    public function track(Request $request) {
        $validator = Validator::make($request->all(), [
            'exchange_id' => 'required|exists:exchanges,exchange_id',
        ]);
        if ($validator->fails()) {
            return responseError('validation_error', $validator->errors());
        }

        $exchange = Exchange::where('exchange_id', $request->exchange_id)
            ->with($this->sendCurrencyRelation, $this->receivedCurrencyRelation)
            ->first();

        $notify = 'Exchange Details';
        return responseSuccess('track_exchange', $notify, [
            'exchange' => $exchange,
        ]);
    }
}
