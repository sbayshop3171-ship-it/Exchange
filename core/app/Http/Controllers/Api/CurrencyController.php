<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function list()
    {
        $notify[] = 'Currency List';

        $currencies = $this->currencyList();
        $imagePath  = route('home') . '/' . getFilePath('currency');

        return responseSuccess('currency', $notify, [
            'currencies' => $currencies,
            'image_path' => $imagePath,
        ]);
    }

    public function sell()
    {
        $notify[] = 'Selling Currency List';

        $currencies = $this->currencyList("availableForSell");
        $imagePath  = getFilePath('currency');

        $expireTime = [
            '6' => "6 hours",
            '12' => "12 hours",
            '24' => "24 hours",
            'week' => "1 week",
            'month' => "1 month",
            '3-months' => "3 months",
        ];
        return responseSuccess('selling_currency', $notify, [
            'currencies' => $currencies,
            'image_path' => $imagePath,
            'expire_time' => $expireTime
        ]);
    }

    public function buy()
    {
        $notify[] = 'Buying Currency List';

        $currencies = $this->currencyList("availableForBuy");
        $imagePath  = getFilePath('currency');

        return responseSuccess('buying_currency', $notify, [
            'currencies' => $currencies,
            'image_path' => $imagePath
        ]);
    }

    private function currencyList($scope = null)
    {
        $currencies = Currency::active()
            ->orderBy('name');
        if ($scope) return $currencies->$scope()->get();
        return $currencies->get();
    }
}
