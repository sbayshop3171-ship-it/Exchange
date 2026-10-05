<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PaymentPopupGateway;
use App\Models\UserExchangeUnlock;
use App\Models\PaymentPopupSetting;
use App\Models\PaymentSubscriptionPlan;
use Illuminate\Http\Request;

class PaymentPopupController extends Controller
{
    public function gateways()
    {
        $settings = PaymentPopupSetting::latest('id')->first();
        $gateways = PaymentPopupGateway::active()->get()->map(function ($gateway) use ($settings) {
            return [
                'id' => $gateway->id,
                'name' => $gateway->name,
                'symbol' => $gateway->symbol,
                'network' => $gateway->network,
                'wallet_address' => $gateway->wallet_address,
                'is_trx_required' => $gateway->is_trx_required || ($settings?->is_trx_required ?? false),
                'is_proof_required' => $gateway->is_proof_required || ($settings?->is_proof_required ?? false),
                'unlock_fee_amount' => $gateway->unlock_fee_amount ?? $settings?->unlock_fee_amount ?? 0,
                'fee_currency' => $gateway->fee_currency ?: ($settings?->fee_currency ?? gs('cur_text')),
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
            'subscription_plan_id' => 'nullable|integer|exists:payment_subscription_plans,id',
            'method' => 'required|string|max:100',
            'trx_id' => 'nullable|string|max:100',
            'payment_proof' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'notes' => 'nullable|string|max:500',
            'is_paid' => 'nullable|in:1',
        ]);

        $gateway = PaymentPopupGateway::findOrFail($request->gateway_id);
        $settings = PaymentPopupSetting::first();

        $request->validate([
            'trx_id' => ($gateway->is_trx_required || $settings?->is_trx_required) ? 'required|string|max:100' : 'nullable|string|max:100',
            'payment_proof' => ($gateway->is_proof_required || $settings?->is_proof_required) ? 'required|image|mimes:jpg,jpeg,png,gif,webp' : 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
        ]);

        if (! $gateway->status) {
            $notify[] = ['error', 'This payment gateway is currently unavailable.'];
            return back()->withNotify($notify);
        }

        $plan = null;
        if ($request->filled('subscription_plan_id')) {
            $plan = PaymentSubscriptionPlan::active()->find($request->integer('subscription_plan_id'));
            if (! $plan) {
                return back()->withInput()->withNotify([['error', 'That subscription plan is no longer available. Please choose another plan.']]);
            }
        }

        $methodLabel = $request->method ?: ($request->is_paid ? 'I Paid' : 'Manual Payment');

        $unlock = new UserExchangeUnlock();
        $unlock->user_id = auth()->id();
        $unlock->gateway_id = $gateway->id;
        $unlock->method = $methodLabel;
        $unlock->trx_id = $request->trx_id;
        $unlock->notes = $request->notes;
        $unlock->status = 'pending';
        if ($plan) {
            $unlock->subscription_plan_id = $plan->id;
            $unlock->subscription_duration_days = $plan->duration_days;
            $unlock->subscription_amount = $plan->amount;
            $unlock->subscription_currency = $plan->currency;
        }

        if ($request->hasFile('payment_proof')) {
            $unlock->payment_proof = fileUploader($request->file('payment_proof'), getFilePath('verify'));
        }

        $unlock->save();

        $notify[] = ['success', 'Your verification request has been submitted. Please wait for admin approval.'];
        return back()->withNotify($notify);
    }
}
