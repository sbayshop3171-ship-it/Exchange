<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentPopupGateway;
use App\Models\UserExchangeUnlock;
use App\Models\PaymentPopupSetting;
use Illuminate\Http\Request;

class PaymentPopupController extends Controller
{
    public function gateways()
    {
        $gateways = PaymentPopupGateway::active()->get()->map(function ($gateway) {
            return [
                'id' => $gateway->id,
                'name' => $gateway->name,
                'symbol' => $gateway->symbol,
                'network' => $gateway->network,
                'wallet_address' => $gateway->wallet_address,
                'is_trx_required' => $gateway->is_trx_required,
                'is_proof_required' => $gateway->is_proof_required,
                'unlock_fee_amount' => $gateway->unlock_fee_amount,
                'fee_currency' => $gateway->fee_currency,
            ];
        });

        return response()->json([
            'success' => true,
            'gateways' => $gateways,
        ]);
    }

    public function storeUnlock(Request $request)
    {
        if (auth()->user()->exchangeUnlocks()->where('status', 'pending')->exists()) {
            return back()->withNotify([['warning', 'Your payment verification is already pending approval.']]);
        }
        $request->validate([
            'gateway_id' => 'required|exists:payment_popup_gateways,id',
            'method' => 'required|string|max:100',
            'trx_id' => 'nullable|string|max:100',
            'payment_proof' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'notes' => 'nullable|string|max:500',
            'is_paid' => 'nullable|in:1',
        ]);

        $gateway = PaymentPopupGateway::findOrFail($request->gateway_id);
        $settings = PaymentPopupSetting::first();

        $request->validate([
            'trx_id' => $settings?->is_trx_required ? 'required|string|max:100' : 'nullable|string|max:100',
            'payment_proof' => $settings?->is_proof_required ? 'required|image|mimes:jpg,jpeg,png,gif,webp' : 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);

        if (! $gateway->status) {
            $notify[] = ['error', 'This payment gateway is currently unavailable.'];
            return back()->withNotify($notify);
        }

        $methodLabel = $request->method ?: ($request->is_paid ? 'I Paid' : 'Manual Payment');

        $unlock = new UserExchangeUnlock();
        $unlock->user_id = auth()->id();
        $unlock->gateway_id = $gateway->id;
        $unlock->method = $methodLabel;
        $unlock->trx_id = $request->trx_id;
        $unlock->notes = $request->notes;
        $unlock->status = 'pending';

        if ($request->hasFile('payment_proof')) {
            $unlock->payment_proof = fileUploader($request->file('payment_proof'), getFilePath('verify'));
        }

        $unlock->save();

        $notify[] = ['success', 'Your verification request has been submitted. Please wait for admin approval.'];
        return back()->withNotify($notify);
    }
}
