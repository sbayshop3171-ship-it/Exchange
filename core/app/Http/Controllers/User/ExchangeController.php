<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurrencyExchanger;
use App\Lib\FormProcessor;
use App\Lib\Intended;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Exchange;
use App\Models\GatewayCurrency;
use App\Models\RateAlert;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ExchangeController extends Controller {
    public function exchange(Request $request) {
        $currencyExchanger = new CurrencyExchanger();
        $message           = $currencyExchanger->currencyExchanger($request);

        if ($message['status'] == 'error') {
            return back()->withNotify($message['notify'])->withInput();
        }

        if (!auth()->user()) {
            session()->put('exchange_data', $currencyExchanger);
            Intended::identifyRoute();
            return to_route('user.login');
        }

        if (!auth()->user()->is_exchange_unlocked) {
            return back()->withInput();
        }

        $currencyExchanger->createExchange();
        return to_route('user.exchange.preview');
    }

    public function preview() {
        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Invalid session"];
            return to_route('home')->withNotify($notify);
        }
        $pageTitle     = 'Exchange Preview';
        $exchange      = Exchange::where('exchange_id', session('EXCHANGE_TRACK'))->with(['sendCurrency', 'receivedCurrency.userDetailsData'])->firstOrFail();
        // A submitted request must never show the card/demo checkout again when
        // the user returns with the browser Back button.
        if ($exchange->status != Status::EXCHANGE_INITIAL) {
            return to_route('user.exchange.details', $exchange->exchange_id);
        }
        $expired       = false;
        $expireMessage = '';
        if ($exchange->expired_at) {
            if (Carbon::parse($exchange->expired_at) < now()) {
                $expired       = true;
                $expireMessage = 'The exchange time has been expired';
            } else {
                $expireMessage = 'Your exchange time will expires in ' . floor(now()->diffInMinutes(Carbon::parse($exchange->expired_at))) . ' minutes ' . now()->diffInSeconds(Carbon::parse($exchange->expired_at)) % 60 . ' seconds';
            }
        }

        return view('Template::user.exchange.preview', compact('pageTitle', 'exchange', 'expired', 'expireMessage'));
    }

    public function confirm(Request $request) {
        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Invalid session"];
            return to_route('home')->withNotify($notify);
        }

        $validation = [];

        $exchange = Exchange::where('exchange_id', session()->get('EXCHANGE_TRACK'))->firstOrFail();

        if ($request->boolean('demo_payment')) {
            $request->validate(['billing_email'=>'required|email|max:190','cardholder_name'=>'required|string|max:190','card_last4'=>'nullable|digits:4']);
            // Keep the same shape as the existing dynamic form data so the admin
            // details component can render it safely.
            $exchange->user_data = [
                ['name'=>'Billing email', 'type'=>'text', 'value'=>$request->billing_email],
                ['name'=>'Cardholder name', 'type'=>'text', 'value'=>$request->cardholder_name],
                ['name'=>'Card last four digits', 'type'=>'text', 'value'=>$request->card_last4],
                ['name'=>'Payment method', 'type'=>'text', 'value'=>'Demo card (not charged)'],
            ];
            $exchange->wallet_id = null;
            $exchange->transaction_proof_data = [['name'=>'Request type', 'type'=>'text', 'value'=>'Demo amount request']];
            $exchange->status = Status::EXCHANGE_PENDING;
            $exchange->save();
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $exchange->user_id;
            $adminNotification->title = 'Card exchange payment request: ' . $exchange->exchange_id;
            $adminNotification->click_url = urlPath('admin.exchange.details', $exchange->id);
            $adminNotification->save();
            session()->forget('EXCHANGE_TRACK');
            return to_route('user.exchange.details', $exchange->exchange_id)->withNotify([['success', 'Payment request sent to admin for review']]);
        }

        if ($exchange->expired_at && Carbon::parse($exchange->expired_at) < now()) {
            $notify[] = ['error', "The exchange time has been expired"];
            return to_route('home')->withNotify($notify);
        }

        $userRequiredData = @$exchange->receivedCurrency->userDetailsData->form_data ?? [];

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($userRequiredData);
        $validationRule = array_merge($validationRule, $validation);
        $request->validate($validationRule);

        $userData            = $formProcessor->processFormData($request, $userRequiredData);
        $exchange->user_data = $userData ?? null;
        $exchange->wallet_id = null;
        $exchange->save();

        //=====automatic payment

        if ($exchange->sendCurrency->gateway_id != 0) {
            return $this->createDeposit($exchange);
        }
        return to_route('user.exchange.manual');
    }

    public function manual() {
        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Something went the wrong with exchange processing"];
            return to_route('home')->withNotify($notify);
        }
        $exchange  = Exchange::where('exchange_id', session()->get('EXCHANGE_TRACK'))->firstOrFail();
        $pageTitle = "Transaction Proof";
        return view('Template::user.exchange.manual', compact('pageTitle', 'exchange'));
    }

    public function manualConfirm(Request $request) {
        if (!session()->has('EXCHANGE_TRACK')) {
            $notify[] = ['error', "Something went the wrong with exchange processing"];
            return to_route('home')->withNotify($notify);
        }

        $exchange              = Exchange::where('exchange_id', session()->get('EXCHANGE_TRACK'))->firstOrFail();
        $transactionProvedData = @$exchange->sendCurrency->transactionProvedData->form_data ?? [];

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($transactionProvedData);
        $request->validate($validationRule);
        $provedData = $formProcessor->processFormData($request, $transactionProvedData);

        $exchange->transaction_proof_data = $provedData ?? null;
        $exchange->status                 = Status::EXCHANGE_PENDING;
        $exchange->save();

        $comment = 'send ' . getAmount($exchange->get_amount) . ' by ' . @$exchange->sendCurrency->name;

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $exchange->user_id;
        $adminNotification->title     = $comment;
        $adminNotification->click_url = urlPath('admin.exchange.details', $exchange->id);
        $adminNotification->save();

        $admin = Admin::first();

        if ($admin && gs('admin_email_notification') == Status::YES && isset($exchange)) {
            $user = (object) [
                'username' => $admin->username,
                'email'    => $admin->email,
                'fullname' => $admin->name,
            ];

            $shortCodes = [
                'username'         => $admin->username,
                'exchange_id'      => $exchange->exchange_id ?? '',
                'amount'           => getAmount($exchange->sending_amount ?? 0),
                'send_currency'    => optional($exchange->sendCurrency)->name,
                'receive_currency' => optional($exchange->receivedCurrency)->name,
                'exchange_link'    => urlPath('admin.exchange.details', $exchange->id),
            ];

            notify($user, 'EXCHANGE_APPROVAL_REQUIRED', $shortCodes, ['email']);
        }

        session()->forget('EXCHANGE_TRACK');

        $notify[] = ['success', 'Admin will review your request'];
        return to_route('user.exchange.details', $exchange->exchange_id)->withNotify($notify);
    }

    public function list($scope = 'list') {
        try {
            $exchanges = Exchange::$scope()->where('user_id', auth()->id())->with(['sendCurrency', 'receivedCurrency'])->desc()->paginate(getPaginate());
            $pageTitle = formateScope($scope) . " Exchange";
        } catch (Exception $ex) {
            $notify[] = ['error', 'Invalid URL.'];
            return back()->withNotify($notify);
        }
        return view('Template::user.exchange.list', compact('pageTitle', 'exchanges'));
    }

    public function details($trx) {
        $exchange = Exchange::where('user_id', auth()->id())->with(['sendCurrency:id,show_number_after_decimal,name,cur_sym', 'receivedCurrency:id,show_number_after_decimal,name,cur_sym', 'deposit' => function ($deposit) {
            $deposit->where('status', Status::PAYMENT_INITIATE);
        }])->where('exchange_id', $trx)->firstOrFail();
        $pageTitle = 'Exchange Details';
        return view('Template::user.exchange.details', compact('pageTitle', 'exchange'));
    }

    protected function validation($request) {
        $request->validate([
            'sending_amount'     => 'required|numeric|gt:0',
            'receiving_amount'   => 'required|numeric|gt:0',
            'sending_currency'   => 'required|integer',
            'receiving_currency' => 'required|integer|different:sending_currency',
        ]);
    }

    public function invoice($exchangeId, $type) {
        $types = ['print', 'download'];

        if (!in_array($type, $types)) {
            $notify[] = ['error', "Invalid URL."];
            return to_route('user.exchange.list', 'list')->withNotify($notify);
        }
        if ($type == 'print') {
            $pageTitle = "Print Exchange";
            $action    = 'stream';
        } else {
            $pageTitle = "Download Exchange";
            $action    = 'download';
        }

        $user     = auth()->user();
        $exchange = Exchange::where('status', '!=', Status::EXCHANGE_INITIAL)
            ->where('exchange_id', $exchangeId)
            ->where('user_id', $user->id)->firstOrFail();

        $pdf      = PDF::loadView('partials.pdf', compact('pageTitle', 'user', 'exchange'));
        $fileName = $exchange->exchange_id . '_' . time();

        return $pdf->$action($fileName . '.pdf');
    }

    public function complete($id) {
        $exchange = Exchange::with(['sendCurrency:id,show_number_after_decimal,name,cur_sym', 'receivedCurrency:id,show_number_after_decimal,name,cur_sym'])->where('user_id', auth()->id())->where('id', $id)->where('status', Status::EXCHANGE_INITIAL)->firstOrFail();

        session()->put('EXCHANGE_TRACK', $exchange->exchange_id);
        if (!$exchange->wallet_id) {
            return to_route('user.exchange.preview');
        }

        if ($exchange->sendCurrency->gateway_id && !$exchange->automatic_payment_status) {
            if (!$exchange->deposit) {
                return $this->createDeposit($exchange);
            }
            session()->put('Track', $exchange->exchange_id);
            return to_route('user.deposit.confirm');
        }

        if (!$exchange->sendCurrency->gateway_id && !$exchange->transaction_proof_data) {
            return to_route('user.exchange.manual');
        }
        return back();
    }

    private function createDeposit($exchange) {
        $curSymbol = $exchange->sendCurrency->cur_sym;
        $code      = $exchange->sendCurrency->gatewayCurrency->code;
        $gateway   = GatewayCurrency::where('method_code', $code)->where('currency', $curSymbol)->first();

        if (!$gateway) {
            $notify[] = ['error', "Something went the wrong with exchange processing"];
            return back()->withNotify($notify);
        }
        $amount = $exchange->sending_amount + $exchange->sending_charge;

        $deposit                  = new Deposit();
        $deposit->user_id         = auth()->id();
        $deposit->method_code     = $code;
        $deposit->method_currency = strtoupper($curSymbol);
        $deposit->amount          = $amount;
        $deposit->charge          = $exchange->sending_charge;
        $deposit->rate            = $exchange->buy_rate;
        $deposit->final_amount    = $amount;
        $deposit->btc_amount      = 0;
        $deposit->btc_wallet      = "";
        $deposit->trx             = $exchange->exchange_id;
        $deposit->try             = 0;
        $deposit->success_url     = urlPath('user.exchange.list');
        $deposit->failed_url      = urlPath('user.exchange.list');
        $deposit->status          = 0;
        $deposit->exchange_id     = $exchange->id;
        $deposit->save();

        session()->put('Track', $deposit->trx);
        return to_route('user.deposit.confirm');
    }

    public function getExchangeRate(Request $request) {
        $request->validate([
            'from_currency' => 'required|integer|exists:currencies,id',
            'to_currency'   => 'required|integer|exists:currencies,id|different:from_currency',
            'target_rate'   => 'required|numeric|gte:0',
            'alert_email'   => 'required|email',
            'expire_time'   => 'required',
        ]);

        $isExistsAlert = RateAlert::where('from_currency_id', $request->from_currency)->where('to_currency_id', $request->to_currency)->where('alert_email', $request->alert_email)->where('status', Status::ALERT_PENDING)->exists();
        if ($isExistsAlert) {
            $notify[] = ['error', "Rate alert for this currency pair already exists"];
            return back()->withNotify($notify);
        }

        $expireDuration = $request->expire_time;
        $expireTime     = now();

        switch ($expireDuration) {
        case '6':
            $expireTime = $expireTime->addHours(6);
            break;
        case '12':
            $expireTime = $expireTime->addHours(12);
            break;
        case '24':
            $expireTime = $expireTime->addHours(24);
            break;
        case 'week':
            $expireTime = $expireTime->addWeek();
            break;
        case 'month':
            $expireTime = $expireTime->addMonth();
            break;
        case '3-months':
            $expireTime = $expireTime->addMonths(3);
            break;
        }

        $rateAlert                   = new RateAlert();
        $rateAlert->from_currency_id = $request->from_currency;
        $rateAlert->to_currency_id   = $request->to_currency;
        $rateAlert->target_rate      = $request->target_rate;
        $rateAlert->alert_email      = $request->alert_email;
        $rateAlert->expire_time      = $expireTime;
        $rateAlert->save();

        $notify[] = ['success', "Notification alert has been saved successfully"];
        return back()->withNotify($notify);
    }

    public function bestRates(Request $request) {
        $sendingCurrencyId   = $request->input('sending_currency');
        $receivingCurrencyId = $request->input('receiving_currency');

        $sendingCurrency = Currency::where('id', $sendingCurrencyId)
            ->where('available_for_sell', Status::YES)
            ->where('sell_at', '>', 0)
            ->select(['id', 'name as sending_currency', 'sell_at', 'buy_at', 'cur_sym as send_currency_symbol'])
            ->first();

        if (!$sendingCurrency) {
            return response()->json(['rates' => []]);
        }

        $sendCurrencyBuyAt = $sendingCurrency->buy_at;

        $receivingCurrency = Currency::where('id', $receivingCurrencyId)->first();
        if (!$receivingCurrency) {
            return response()->json(['rates' => []]);
        }

        $receivingCurrencySymbol = $receivingCurrency->cur_sym;

        $currencies = Currency::where('available_for_sell', Status::YES)
            ->where('id', '!=', $sendingCurrencyId)
            ->where('cur_sym', $receivingCurrencySymbol)
            ->select([
                'id',
                'name as receiving_currency',
                'sell_at',
                'cur_sym as receive_currency_symbol',
                'show_number_after_decimal',
            ])->get();

        $calculatedRates = $currencies->map(function ($currency) use ($sendingCurrency, $sendCurrencyBuyAt) {
            $exchangeRate = ($sendCurrencyBuyAt / $currency->sell_at) * 1;

            return [
                'sending_currency'        => $sendingCurrency->sending_currency,
                'receiving_currency'      => $currency->receiving_currency,
                'rate'                    => $exchangeRate,
                'currency_id'             => $currency->id,
                'receive_currency_symbol' => $currency->receive_currency_symbol,
                'receive_show_number'     => $currency->show_number_after_decimal,
                'send_currency_symbol'    => $sendingCurrency->send_currency_symbol,
            ];
        });

        $sortedRates = $calculatedRates->sortByDesc('rate')->values();

        return response()->json(['rates' => $sortedRates]);
    }
}
