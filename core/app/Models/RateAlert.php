<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateAlert extends Model {
    public function fromCurrency() {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }
    public function toCurrency() {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }

    public function getCurrentRate() {
        $fromCurrency = $this->fromCurrency;
        $toCurrency   = $this->toCurrency;

        if ($fromCurrency && $toCurrency) {
            return $fromCurrency->sell_at / $toCurrency->buy_at;
        }
        return null;
    }
}
