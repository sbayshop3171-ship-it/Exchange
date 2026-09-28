<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\CommissionLog;
use App\Models\Currency;
use App\Models\Exchange;
use App\Models\Referral;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller {
    public function index($scope) {
        try {
            $exchanges = Exchange::$scope()->desc()->with('user', 'sendCurrency', 'receivedCurrency')->searchable(['exchange_id', 'user:username'])->filter(['user_id'])->dateFilter()->paginate(getPaginate());
            $pageTitle = formateScope($scope) . ' Exchange';
        } catch (Exception $ex) {
            $notify[] = ['error', "Invalid URL"];
            return to_route('admin.exchange.list', 'list')->withNotify($notify);
        }
        $columns = ['exchange_id', 'user_id', 'receive_currency_id', 'receiving_amount', 'send_currency_id', 'sending_amount', 'status'];
        return view('admin.exchange.list', compact('pageTitle', 'exchanges', 'columns', 'scope'));
    }

    public function exportExchanges(Request $request) {
        $exportColumns = $request->columns;
        $query         = Exchange::with(['user', 'sendCurrency', 'receivedCurrency']);
        if ($request->has('scope')) {
            switch ($request->scope) {
            case 'pending':
                $query->where('status', Status::EXCHANGE_PENDING);
                break;
            case 'approved':
                $query->where('status', Status::EXCHANGE_APPROVED);
                break;
            case 'refunded':
                $query->where('status', Status::EXCHANGE_REFUND);
                break;
            case 'canceled':
                $query->where('status', Status::EXCHANGE_CANCEL);
                break;
            default:
                break;
            }
        }
        $orderBy = $request->order_by ?? 'desc';
        $query->orderBy('created_at', $orderBy);

        $exchanges = $query->take($request->export_item)->get();

        $data = $exchanges->map(function ($exchange) use ($exportColumns) {
            $row = [];
            foreach ($exportColumns as $column) {
                switch ($column) {
                case 'user_id':
                    $row['User Fullname'] = optional($exchange->user)->fullname;
                    $row['User Username'] = optional($exchange->user)->username;
                    break;
                case 'send_currency_id':
                    $row['Send Currency'] = optional($exchange->sendCurrency)->name;
                    break;
                case 'receive_currency_id':
                    $row['Received Currency'] = optional($exchange->receivedCurrency)->name;
                    break;
                case 'sending_amount':
                    $row['Sending Amount'] = number_format($exchange->sending_amount, $exchange->sendCurrency->show_number_after_decimal);
                    break;
                case 'receiving_amount':
                    $row['Receiving Amount'] = number_format($exchange->receiving_amount, $exchange->receivedCurrency->show_number_after_decimal);
                    break;
                case 'status':
                    if ($exchange->status == Status::EXCHANGE_INITIAL) {
                        $row['Status'] = 'Initiated';
                    } else if ($exchange->status == Status::EXCHANGE_APPROVED) {
                        $row['Status'] = 'Approved';
                    } else if ($exchange->status == Status::EXCHANGE_PENDING) {
                        $row['Status'] = 'Pending';
                    } else if ($exchange->status == Status::EXCHANGE_REFUND) {
                        $row['Status'] = 'Refunded';
                    } else {
                        $row['Status'] = 'Cancelled';
                    }
                    break;
                default:
                    $row[ucwords(str_replace('_', ' ', $column))] = $exchange->$column;
                    break;
                }
            }
            return $row;
        });

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array_keys($data->first() ?? []));
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 'exchange_export.csv');
    }

    public function details($id) {
        $exchange  = Exchange::where('id', $id)->firstOrFail();
        $pageTitle = 'Exchange Details: ' . $exchange->exchange_id;
        return view('admin.exchange.details', compact('pageTitle', 'exchange'));
    }

    public function cancel(Request $request, $id) {
        $request->validate([
            'cancel_reason' => 'required',
        ]);

        DB::transaction(function () use ($id, $request) {
            $exchange = Exchange::where('id', $id)->pending()->lockForUpdate()->firstOrFail();
            $exchange->admin_feedback = $request->cancel_reason;
            $exchange->status         = Status::EXCHANGE_CANCEL;
            $exchange->save();

            notify($exchange->user, 'CANCEL_EXCHANGE', [
                'exchange' => $exchange->exchange_id,
                'reason'   => $exchange->admin_feedback,
            ]);
        });

        $notify[] = ['success', 'Exchange canceled successfully'];
        return back()->withNotify($notify);
    }

    public function refund(Request $request, $id) {
        $request->validate([
            'refund_reason' => 'required',
        ]);

        DB::transaction(function () use ($id, $request) {
            $exchange = Exchange::where('id', $id)->pending()->lockForUpdate()->firstOrFail();
            $exchange->admin_feedback = $request->refund_reason;
            $exchange->status         = Status::EXCHANGE_REFUND;
            $exchange->save();

            notify($exchange->user, 'EXCHANGE_REFUND', [
                'exchange' => $exchange->exchange_id,
                'currency' => $exchange->sendCurrency->cur_sym,
                'amount'   => showAmount($exchange->sending_amount, currencyFormat: false),
                'method'   => $exchange->sendCurrency->name,
                'reason'   => $exchange->admin_feedback,
            ]);
        });

        $notify[] = ['success', 'Exchange refunded successfully'];
        return back()->withNotify($notify);
    }

    public function approve(Request $request, $id) {
        $request->validate([
            'transaction' => 'required',
        ]);

        DB::transaction(function () use ($id, $request) {
            $exchange = Exchange::where('id', $id)->pending()->lockForUpdate()->firstOrFail();
            $user = User::lockForUpdate()->findOrFail($exchange->user_id);
            $sendCurrency = Currency::lockForUpdate()->findOrFail($exchange->receive_currency_id);
            $receivedCurrency = Currency::lockForUpdate()->findOrFail($exchange->send_currency_id);

            $exchange->status       = Status::EXCHANGE_APPROVED;
            $exchange->admin_trx_no = $request->transaction;
            $exchange->save();

            if (gs('exchange_commission') == Status::YES) {
                $amount = $exchange->buy_rate * $exchange->sending_amount;
                $this->levelCommission($user->id, $amount, 'exchange_commission');
            }

            $sendCurrency->reserve -= ($exchange->receiving_amount - $exchange->receiving_charge);
            $sendCurrency->save();

            $receivedCurrency->reserve += ($exchange->sending_amount + $exchange->sending_charge);
            $receivedCurrency->save();

            // The approved exchange amount is credited to the user's dashboard
            // balance. The exchange is locked and must be pending, so this is
            // applied once per approval only.
            $approvedAmount = $exchange->receiving_amount - $exchange->receiving_charge;
            $user->balance += $approvedAmount;
            $user->save();

            if (gs('first_exchange_bonus')) {
                $isFirstExchange = Exchange::where('user_id', $user->id)->where('status', Status::EXCHANGE_APPROVED)->count();
                if ($isFirstExchange == 1) {
                    $receivedAmount                 = $exchange->receiving_amount * (gs('first_exchange_bonus_percentage') / 100);
                    $receivedCurrencyConversionRate = getAmount($sendCurrency->conversion_rate);
                    $convertedAmount                = $receivedAmount * $receivedCurrencyConversionRate;

                    $exchange->bonus_first_exchange = $convertedAmount;
                    $exchange->save();

                    $user->balance += $convertedAmount;
                    $user->save();

                    notify($user, 'BONUS_RECEIVED', [
                        'exchange' => $exchange->exchange_id,
                        'amount'   => showAmount($convertedAmount, currencyFormat: false),
                        'currency' => gs('cur_text'),
                    ]);
                }
            }

            notify($user, 'APPROVED_EXCHANGE', [
                'exchange'                 => $exchange->exchange_id,
                'currency'                 => $sendCurrency->cur_sym,
                'amount'                   => showAmount($exchange->receiving_amount - $exchange->receiving_charge, currencyFormat: false),
                'method'                   => $sendCurrency->name,
                'admin_transaction_number' => $request->transaction,
            ]);
        });

        $notify[] = ['success', 'Exchange approved successfully'];
        return back()->withNotify($notify);
    }

    public function levelCommission($id, $amount, $commissionType = '') {
        $usr   = $id;
        $i     = 1;
        $level = min(5, (int) Referral::max('level'));
        $visited = [];

        while ($usr && $usr !== "0" && $i <= $level) {
            if (in_array((int) $usr, $visited, true)) {
                break;
            }
            $visited[] = (int) $usr;

            $me = User::find($usr);
            if (!$me || !$me->ref_by) {
                break;
            }
            $refer = User::find($me->ref_by);
            if (!$refer) {
                break;
            }

            $commission = Referral::where('level', $i)->first();
            if ($commission == null) {
                break;
            }

            $com                  = ($amount * $commission->percent) / 100;
            $referWallet          = User::where('id', $refer->id)->first();
            $newBal               = getAmount($referWallet->balance + $com);
            $referWallet->balance = $newBal;
            $referWallet->save();

            $trx = getTrx();

            $commission           = new CommissionLog();
            $commission->user_id  = $refer->id;
            $commission->who      = $id;
            $commission->level    = $i . ' level Referral Commission';
            $commission->amount   = getAmount($com);
            $commission->main_amo = $newBal;
            $commission->title    = $commissionType;
            $commission->trx      = $trx;
            $commission->save();

            notify($refer, 'REFERRAL_COMMISSION', [
                'amount'       => getAmount($com),
                'post_balance' => $newBal,
                'trx'          => $trx,
                'level'        => $i . ' level Referral Commission',
            ]);

            $usr = $refer->id;
            $i++;
        }
        return 0;
    }

    public function download($exchangeId) {
        $pageTitle = "Download Exchange";
        $exchange  = Exchange::where('id', $exchangeId)->with('user')->firstOrFail();
        $user      = $exchange->user;
        $pdf       = PDF::loadView('partials.pdf', compact('pageTitle', 'user', 'exchange'));
        $fileName  = $exchange->exchange_id . '_' . time();

        return $pdf->download($fileName . '.pdf');
    }
}
