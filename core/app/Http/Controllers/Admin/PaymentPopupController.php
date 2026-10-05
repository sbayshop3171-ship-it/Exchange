<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\PaymentPopupGateway;
use App\Models\UserExchangeUnlock;
use App\Models\User;
use App\Models\PaymentPopupSetting;
use App\Models\PaymentSubscriptionPlan;
use Illuminate\Http\Request;

class PaymentPopupController extends Controller
{
    public function index()
    {
        $pageTitle = 'Payment Popup Section';
        $gateways = PaymentPopupGateway::orderBy('id', 'desc')->get();
        $requests = UserExchangeUnlock::with(['user', 'gateway', 'subscriptionPlan'])->orderBy('id', 'desc')->get();
        $settings = PaymentPopupSetting::firstOrCreate([], ['fee_currency' => gs('cur_text')]);

        return view('admin.payment_popup.index', compact('pageTitle', 'gateways', 'requests', 'settings'));
    }

    public function plans()
    {
        $pageTitle = 'Subscription Plans';
        $plans = PaymentSubscriptionPlan::orderBy('duration_days')->orderBy('id')->get();

        return view('admin.payment_popup.plans', compact('pageTitle', 'plans'));
    }

    public function storePlan(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'duration_days' => 'required|integer|min:1|max:36500',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:20',
            'status' => 'nullable|in:on,1',
        ]);
        $data['status'] = $request->boolean('status');
        PaymentSubscriptionPlan::create($data);

        return back()->withNotify([['success', 'Subscription plan created successfully']]);
    }

    public function updatePlan(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'duration_days' => 'required|integer|min:1|max:36500',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:20',
            'status' => 'nullable|in:on,1',
        ]);
        $data['status'] = $request->boolean('status');
        PaymentSubscriptionPlan::findOrFail($id)->update($data);

        return back()->withNotify([['success', 'Subscription plan updated successfully']]);
    }

    public function deletePlan($id)
    {
        PaymentSubscriptionPlan::findOrFail($id)->delete();

        return back()->withNotify([['success', 'Subscription plan deleted successfully']]);
    }

    public function planStatus($id)
    {
        $plan = PaymentSubscriptionPlan::findOrFail($id);
        $plan->status = ! $plan->status;
        $plan->save();

        return back()->withNotify([['success', 'Subscription plan status updated']]);
    }

    public function updateSettings(Request $request) {
        $data = $request->validate(['unlock_fee_amount'=>'required|numeric|min:0','fee_currency'=>'required|string|max:20','vat_rate'=>'nullable|numeric|min:0','fixed_fee_amount'=>'nullable|numeric|min:0']);
        $settings = PaymentPopupSetting::firstOrCreate([]);
        $settings->update($data + ['is_trx_required'=>$request->boolean('is_trx_required'),'is_proof_required'=>$request->boolean('is_proof_required'),'vat_enabled'=>$request->boolean('vat_enabled'),'fixed_fee_enabled'=>$request->boolean('fixed_fee_enabled')]);
        return back()->withNotify([['success','Payment popup settings updated successfully']]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'symbol' => 'required|string|max:20',
            'network' => 'nullable|string|max:80',
            'wallet_address' => 'nullable|string|max:255',
            'qr_code_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'status' => 'nullable|in:on,1',
            'is_trx_required' => 'nullable|in:on,1',
            'is_proof_required' => 'nullable|in:on,1',
            'unlock_fee_amount' => 'nullable|numeric|min:0',
            'fee_currency' => 'nullable|string|max:20',
        ]);

        $gateway = new PaymentPopupGateway();
        $gateway->name = $request->name;
        $gateway->symbol = $request->symbol;
        $gateway->network = $request->network;
        $gateway->wallet_address = $request->wallet_address;
        $gateway->status = $request->status ? Status::YES : Status::NO;
        $gateway->is_trx_required = $request->boolean('is_trx_required');
        $gateway->is_proof_required = $request->boolean('is_proof_required');
        $gateway->unlock_fee_amount = $request->filled('unlock_fee_amount') ? $request->unlock_fee_amount : null;
        $gateway->fee_currency = $request->filled('fee_currency') ? $request->fee_currency : null;

        if ($request->hasFile('qr_code_image')) {
            $gateway->qr_code_image = fileUploader($request->file('qr_code_image'), getFilePath('verify'));
        }

        $gateway->save();

        $notify[] = ['success', 'Payment gateway created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:80',
            'symbol' => 'required|string|max:20',
            'network' => 'nullable|string|max:80',
            'wallet_address' => 'nullable|string|max:255',
            'qr_code_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'is_trx_required' => 'nullable|in:on,1',
            'is_proof_required' => 'nullable|in:on,1',
            'unlock_fee_amount' => 'nullable|numeric|min:0',
            'fee_currency' => 'nullable|string|max:20',
        ]);

        $gateway = PaymentPopupGateway::findOrFail($id);
        $gateway->name = $request->name;
        $gateway->symbol = $request->symbol;
        $gateway->network = $request->network;
        $gateway->wallet_address = $request->wallet_address;
        $gateway->status = $request->status ? Status::YES : Status::NO;
        $gateway->is_trx_required = $request->boolean('is_trx_required');
        $gateway->is_proof_required = $request->boolean('is_proof_required');
        $gateway->unlock_fee_amount = $request->filled('unlock_fee_amount') ? $request->unlock_fee_amount : null;
        $gateway->fee_currency = $request->filled('fee_currency') ? $request->fee_currency : null;

        if ($request->hasFile('qr_code_image')) {
            // The third fileUploader argument is the resize size; pass the
            // existing filename as the fourth argument so image updates do
            // not try to resize using a filename such as "1".
            $gateway->qr_code_image = fileUploader($request->file('qr_code_image'), getFilePath('verify'), null, $gateway->qr_code_image);
        }

        $gateway->save();

        $notify[] = ['success', 'Payment gateway updated successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $gateway = PaymentPopupGateway::findOrFail($id);
        $gateway->delete();

        $notify[] = ['success', 'Payment gateway removed successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $gateway = PaymentPopupGateway::findOrFail($id);
        $gateway->status = ! $gateway->status;
        $gateway->save();

        $notify[] = ['success', 'Gateway status updated successfully'];
        return back()->withNotify($notify);
    }

    public function approveRequest($id)
    {
        $request = UserExchangeUnlock::with('user')->findOrFail($id);
        $alreadyApproved = $request->status === 'approved';
        $request->status = 'approved';
        $request->save();

        $user = User::findOrFail($request->user_id);
        $user->is_exchange_unlocked = true;
        if (! $alreadyApproved && $request->subscription_duration_days) {
            $start = $user->exchange_unlocked_until && $user->exchange_unlocked_until->isFuture()
                ? $user->exchange_unlocked_until
                : now();
            $user->exchange_unlocked_until = $start->copy()->addDays($request->subscription_duration_days);
        } elseif (! $alreadyApproved) {
            // A standard gateway approval is the original permanent unlock flow.
            $user->exchange_unlocked_until = null;
        }
        $user->save();

        $notify[] = ['success', 'Verification approved successfully'];
        return back()->withNotify($notify);
    }

    public function rejectRequest($id)
    {
        $request = UserExchangeUnlock::with('user')->findOrFail($id);
        $request->status = 'rejected';
        $request->save();

        $user = User::findOrFail($request->user_id);
        $user->is_exchange_unlocked = false;
        $user->save();

        $notify[] = ['success', 'Verification rejected successfully'];
        return back()->withNotify($notify);
    }
}
