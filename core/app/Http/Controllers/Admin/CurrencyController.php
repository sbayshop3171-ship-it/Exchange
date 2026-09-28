<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gateway;
use App\Models\Currency;
use App\Constants\Status;
use App\Lib\FormProcessor;
use Illuminate\Http\Request;
use App\Models\GatewayCurrency;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::searchable(['name', 'cur_sym'])->latest()->paginate(getPaginate());
        $pageTitle  = "Manage Currency";
        return view('admin.currency.index', compact('pageTitle', 'currencies'));
    }
    public function create()
    {
        $gateways  = Gateway::automatic()->active()->latest()->get();
        $pageTitle = "Create Currency";
        return view('admin.currency.create', compact('pageTitle', 'gateways'));
    }

    public function edit($id)
    {
        $gateways  = Gateway::automatic()->active()->latest()->get();
        $pageTitle = "Edit Currency";
        $currency  = Currency::where('id', $id)->with('userDetailsData', 'transactionProvedData')->firstOrFail();
        return view('admin.currency.create', compact('pageTitle', 'gateways', 'currency'));
    }

    public function save(Request $request, $id = 0)
    {
        $this->validation($request, $id);
        $currencySymbol = strtoupper($request->currency);

        if ($request->payment_gateway > 0) {
            $gateway         = Gateway::automatic()->active()->where('id', $request->payment_gateway)->first();
            $gatewayCurrency = GatewayCurrency::where('method_code', $gateway->code)->where('currency', $currencySymbol)->first();

            if (!$gatewayCurrency) {
                $notify[] = ['info', "Please add $currencySymbol support currency under the  $gateway->name  payment method"];
                $notify[] = ['error', "Gateway currency & Currency symbol must be same."];
                return back()->withNotify($notify)->withInput();
            }
        }

        if ($id) {
            $currency = Currency::findOrFail($id);
            $message  = "Currency updated successfully";
        } else {
            $currency = new Currency();
            $message  = "Currency added successfully";
        }

        $currency->name                      = $request->name;
        $currency->cur_sym                   = $currencySymbol;
        $currency->conversion_rate           = $request->conversion_rate;
        $currency->percent_decrease          = $request->percent_decrease;
        $currency->percent_increase          = $request->percent_increase;
        $currency->sell_at                   = $request->sell_at;
        $currency->add_automatic_rate        = $request->add_automatic_rate;
        $currency->buy_at                    = $request->buy_at;
        $currency->minimum_limit_for_sell    = $request->minimum_limit_for_sell;
        $currency->maximum_limit_for_sell    = $request->maximum_limit_for_sell;
        $currency->minimum_limit_for_buy     = $request->minimum_limit_for_buy;
        $currency->maximum_limit_for_buy     = $request->maximum_limit_for_buy;
        $currency->fixed_charge_for_sell     = $request->fixed_charge_for_sell;
        $currency->percent_charge_for_sell   = $request->percent_charge_for_sell;
        $currency->fixed_charge_for_buy      = $request->fixed_charge_for_buy;
        $currency->percent_charge_for_buy    = $request->percent_charge_for_buy;
        $currency->reserve                   = $request->reserve;
        $currency->instruction               = $request->instruction;
        $currency->available_for_sell        = $request->available_for_sell ? Status::YES : Status::NO;
        $currency->available_for_buy         = $request->available_for_buy ? Status::YES : Status::NO;
        $currency->show_rate                 = $request->rate_show ? Status::YES : Status::NO;
        $currency->automatic_rate_update     = $request->automatic_rate_update ? Status::YES : Status::NO;
        $currency->show_number_after_decimal = $request->show_number_after_decimal;
        $currency->gateway_id                = $request->payment_gateway;
        $currency->user_detail_form_id       = 0;
        $currency->trx_proof_form_id         = 0;

        if ($request->hasFile('image')) {
            try {
                $path            = getFilePath('currency');
                $size            = getFileSize('currency');
                $currency->image = fileUploader($request->image, $path, $size, $currency->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $currency->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Currency::changeStatus($id);
    }

    protected function validation($request, $id)
    {
        $imageValidation = $id ? 'sometimes' : 'required';

        $rules = [
            'name'                    => 'required|max:255',
            'currency'                => 'required|max:255',
            'payment_gateway'         => 'required|integer|min:0',
            'conversion_rate'         => 'required|numeric|gte:0',
            'percent_decrease'        => 'required|numeric|gte:0',
            'percent_increase'        => 'required|numeric|gte:0',
            'buy_at'                  => 'required|numeric|gte:0',
            'sell_at'                 => 'required|numeric|gte:0',
            'add_automatic_rate'      => 'required|numeric|gte:0',
            'reserve'                 => 'required|numeric|gte:0',
            'instruction'             => 'required',
            'minimum_limit_for_sell'  => 'required',
            'minimum_limit_for_sell'  => "required|numeric|gte:0",
            'maximum_limit_for_sell'  => "required|numeric|gt:minimum_limit_for_sell",
            'fixed_charge_for_sell'   => "required|numeric|gte:0",
            'percent_charge_for_sell' => "required|numeric|gte:0",
            'minimum_limit_for_buy'   => 'required|numeric|gte:0',
            'maximum_limit_for_buy'   => 'required|numeric|gt:minimum_limit_for_buy',
            'fixed_charge_for_buy'    => "required|numeric|gte:0",
            'percent_charge_for_buy'  => "required|numeric|gte:0",
            'show_number_after_decimal'  => "required|integer|min:2|max:8",
            'image'                   => [$imageValidation, new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ];

        $request->validate($rules);
    }

    public function updateApiKey(Request $request)
    {
        $request->validate([
            'api_key' => 'required',
        ]);

        $general                   = gs();
        $general->currency_api_key = $request->api_key;
        $general->save();

        $notify[] = ['success', 'Api key updated successfully'];
        return back()->withNotify($notify);
    }

    public function transactionProofForm($id)
    {
        $pageTitle = "Transaction Proof Form";
        $currency  = Currency::where('id', $id)->with('transactionProvedData')->firstOrFail();
        $type      = 'transaction_proof';
        $form      = @$currency->transactionProvedData;
        return view('admin.currency.form', compact('pageTitle', 'currency', 'type', 'form'));
    }

    public function sendingForm($id)
    {
        $pageTitle = "Sending Form";
        $currency  = Currency::where('id', $id)->with('userDetailsData')->firstOrFail();
        $type      = 'sending';
        $form      = @$currency->userDetailsData;
        return view('admin.currency.form', compact('pageTitle', 'currency', 'type', 'form'));
    }

    public function formSubmit(Request $request, $id)
    {
        $currency            = Currency::findOrFail($id);
        $formProcessor       = new FormProcessor();
        $generatorValidation = $formProcessor->generatorValidation();
        $request->validate($generatorValidation['rules'], $generatorValidation['messages']);

        if ($request->form_type == 'transaction_proof') {
            if ($currency->trx_proof_form_id) {
                $formData = $formProcessor->generate('currency', true, 'id', $currency->trx_proof_form_id);
            } else {
                $formData = $formProcessor->generate('currency');
            }
            $currency->trx_proof_form_id = $formData->id;
        } elseif ($request->form_type == 'sending') {
            if ($currency->user_detail_form_id) {
                $formData = $formProcessor->generate('currency', true, 'id', $currency->user_detail_form_id);
            } else {
                $formData = $formProcessor->generate('currency');
            }
            $currency->user_detail_form_id = $formData->id;
        }
        $currency->save();

        $notify[] = ['success', 'Form updated successfully'];
        return back()->withNotify($notify);
    }

    public function conversionRate(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL        => 'https://v6.exchangerate-api.com/v6/' . gs('currency_api_key') . '/pair/' . $request->currency . '/' . gs('cur_text'),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET"
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return response()->json([json_decode($response)]);
    }
}
