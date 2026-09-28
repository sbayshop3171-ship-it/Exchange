<?php

namespace App\Lib;

use App\Models\Currency;
use App\Models\Exchange;
use Exception;
use Illuminate\Http\Request;

class CurrencyExchanger {
    protected $sendCurrency;
    protected $receiveCurrency;
    protected $sendAmount;
    protected $receiveAmount;
    protected $charge;
    public $exchange;

    public function currencyExchanger(Request $request) {
        $this->validation($request);

        $this->sendCurrency    = Currency::enabled()->availableForSell()->find($request->sending_currency);
        $this->receiveCurrency = Currency::enabled()->availableForBuy()->find($request->receiving_currency);

        if (!$this->sendCurrency) {
            $notify[] = ['error', 'Sending currency not found'];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        if (!$this->receiveCurrency) {
            $notify[] = ['error', 'Receiving currency not found'];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        $this->sendAmount = $request->sending_amount;
        try {
            $sendingPercentCharge = $this->sendAmount / 100 * $this->sendCurrency->percent_charge_for_buy;
            $sendingFixedCharge   = $this->sendCurrency->fixed_charge_for_buy;
            $totalSendingCharge   = $sendingFixedCharge + $sendingPercentCharge;
            $this->receiveAmount  = $this->sendCurrency->buy_at / $this->receiveCurrency->sell_at * $this->sendAmount;

            $receivingPercentCharge = $this->receiveAmount / 100 * $this->receiveCurrency->percent_charge_for_sell;
            $receivingFixedCharge   = $this->receiveCurrency->fixed_charge_for_sell;
            $totalReceivingCharge   = $receivingFixedCharge + $receivingPercentCharge;
            $totalReceivedAmount    = $this->receiveAmount - $totalReceivingCharge;
        } catch (Exception $ex) {
            $notify[] = ['error', "Something went wrong with the exchange processing."];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        if ($this->sendAmount < $this->sendCurrency->minimum_limit_for_buy) {
            $notify[] = ['error', "Minimum sending amount " . number_format($this->sendCurrency->minimum_limit_for_buy, $this->sendCurrency->show_number_after_decimal) . ' ' . $this->sendCurrency->cur_sym];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        if ($this->sendAmount > $this->sendCurrency->maximum_limit_for_buy) {
            $notify[] = ['error', "Maximum sending amount " . number_format($this->sendCurrency->maximum_limit_for_buy, $this->sendCurrency->show_number_after_decimal) . ' ' . $this->sendCurrency->cur_sym];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        if ($this->receiveAmount < $this->receiveCurrency->minimum_limit_for_sell) {
            $notify[] = ['error', "Minimum received amount " . number_format($this->receiveCurrency->minimum_limit_for_sell, $this->receiveCurrency->show_number_after_decimal) . ' ' . $this->receiveCurrency->cur_sym];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        if ($this->receiveAmount > $this->receiveCurrency->maximum_limit_for_sell) {
            $notify[] = ['error', "Maximum received amount " . number_format($this->receiveCurrency->maximum_limit_for_sell, $this->receiveCurrency->show_number_after_decimal) . ' ' . $this->receiveCurrency->cur_sym];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        if ($totalReceivedAmount > $this->receiveCurrency->reserve) {
            $notify[] = ['error', "Sorry, our reserve limit exceeded"];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }


        if ($totalReceivedAmount <= 0) {
            $notify[] = ['error', 'Negative amount is not acceptable'];
            return [
                'status' => 'error',
                'notify' => $notify,
            ];
        }

        $this->charge = [
            'sending_charge'   => [
                'fixed_charge'   => $sendingFixedCharge,
                'percent_charge' => $this->sendCurrency->percent_charge_for_buy,
                'percent_amount' => $sendingPercentCharge,
                'total_charge'   => $totalSendingCharge,
            ],
            'receiving_charge' => [
                'fixed_charge'   => $receivingFixedCharge,
                'percent_charge' => $this->receiveCurrency->percent_charge_for_sell,
                'percent_amount' => $receivingPercentCharge,
                'total_charge'   => $totalReceivingCharge,
            ],
        ];
        return [
            'status' => 'success',
        ];
    }

    public function createExchange() {
        $this->exchange                      = new Exchange();
        $this->exchange->user_id             = auth()->id();
        $this->exchange->send_currency_id    = $this->sendCurrency->id;
        $this->exchange->receive_currency_id = $this->receiveCurrency->id;
        $this->exchange->sending_amount      = $this->sendAmount;
        $this->exchange->sending_charge      = $this->charge['sending_charge']['total_charge'];
        $this->exchange->receiving_amount    = $this->receiveAmount;
        $this->exchange->receiving_charge    = $this->charge['receiving_charge']['total_charge'];
        $this->exchange->sell_rate           = $this->receiveCurrency->sell_at;
        $this->exchange->buy_rate            = $this->sendCurrency->buy_at;
        $this->exchange->exchange_id         = getTrx();
        $this->exchange->charge              = $this->charge;
        if (gs('exchange_auto_cancel')) {
            $this->exchange->expired_at = now()->addMinutes(gs('exchange_auto_cancel_time'));
        }
        $this->exchange->save();
        session()->put('EXCHANGE_TRACK', $this->exchange->exchange_id);
    }

    protected function validation($request) {
        $request->validate([
            'sending_amount'     => 'required|numeric|gt:0',
            'receiving_amount'   => 'required|numeric|gt:0',
            'sending_currency'   => 'required|integer',
            'receiving_currency' => 'required|integer|different:sending_currency',
        ]);
    }
}
