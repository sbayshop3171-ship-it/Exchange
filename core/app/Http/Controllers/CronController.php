<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Lib\CurlRequest;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\Currency;
use App\Models\Exchange;
use App\Models\RateAlert;
use Carbon\Carbon;

class CronController extends Controller {
    public function fiatRate() {
        if (!gs('automatic_currency_rate_update')) {
            return 0;
        }

        try {
            $currencies = Currency::where('automatic_rate_update', Status::YES)->get();
            if ($currencies->count() > 0) {
                foreach ($currencies as $currency) {
                    $currencyRate = $this->currencyRate($currency->cur_sym);

                    if ($currencyRate['result'] != 'success' || !$currencyRate['conversion_rate']) {
                        continue;
                    }
                    $currencyRate = $currencyRate['conversion_rate'];
                    $currencyRate += ($currencyRate / 100) * $currency->add_automatic_rate;

                    $percentDecValue = ($currencyRate / 100) * $currency->percent_decrease;
                    $percentInValue  = ($currencyRate / 100) * $currency->percent_increase;

                    $currency->conversion_rate = $currencyRate;
                    $currency->buy_at          = $currencyRate - $percentDecValue;
                    $currency->sell_at         = $currencyRate + $percentInValue;
                    $currency->save();
                }
            }
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function rateAlert() {
        if (!gs('automatic_currency_rate_update')) {
            return 0;
        }
        $alerts = RateAlert::where('status', Status::ALERT_PENDING)->where('expire_time', '>', now())->with(['fromCurrency', 'toCurrency'])->limit(30)->get();
        foreach ($alerts as $alert) {
            $currentRate = $alert->getCurrentRate();
            if ($currentRate && $currentRate >= $alert->target_rate) {

                $alert->status = Status::ALERT_COMPLETED;
                $alert->save();

                $user = [
                    'fullname'     => null,
                    'mobileNumber' => null,
                    'username'     => null,
                    'email'        => $alert->alert_email,
                ];

                $fromCurrency = $alert->fromCurrency;
                $toCurrency   = $alert->toCurrency;

                $shortCodes = [
                    'from_currency' => $fromCurrency->cur_sym ?? $fromCurrency->name,
                    'to_currency'   => $toCurrency->cur_sym ?? $toCurrency->name,
                    'target_rate'   => $alert->target_rate,
                    'current_rate'  => $currentRate,
                ];

                notify((object) $user, 'RATE_ALERT_NOTIFICATION', $shortCodes, ['email']);
            }
        }
    }

    public function exchangeAutoCancel() {
        $exchanges = Exchange::where('status', Status::EXCHANGE_INITIAL)->where('expired_at', '<', now())->limit(50)->get();
        foreach ($exchanges as $exchange) {
            $exchange->status = Status::EXCHANGE_CANCEL;
            $exchange->save();
        }
    }

    public function checkExpiredAlerts() {
        $expiredAlerts = RateAlert::where('status', Status::ALERT_PENDING)
            ->whereNotNull('expire_time')
            ->where('expire_time', '<', now())
            ->get();

        foreach ($expiredAlerts as $alert) {
            $alert->status = Status::ALERT_COMPLETED;
            $alert->save();
        }
    }

    protected function currencyRate($currency) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => 'https://v6.exchangerate-api.com/v6/' . gs('currency_api_key') . '/pair/' . $currency . '/' . gs('cur_text'),
            CURLOPT_HTTPHEADER     => array(
                "Content-Type: text/plain",
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return ['result' => $response->result, 'conversion_rate' => $response->conversion_rate];
    }

    public function cron() {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');
        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }
}
