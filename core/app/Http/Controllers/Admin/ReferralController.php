<?php

namespace App\Http\Controllers\Admin;

use App\Models\Referral;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReferralController extends Controller
{
    public function index()
    {
        $pageTitle = "Manage Referral";
        $referrals = Referral::desc()->get();
        return view('admin.referrals.index', compact('pageTitle', 'referrals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'commission'           => 'required|array',
            'commission.*.level'   => 'required|integer|min:1',
            'commission.*.percent' => 'required|numeric|gte:0',
        ]);

        Referral::truncate();
        Referral::insert($request->commission);

        $notify[] = ['success', 'Referral level added successfully'];
        return back()->withNotify($notify);
    }

    public function status()
    {
        $general                      = gs();
        $general->exchange_commission = !$general->exchange_commission;
        $general->save();

        $notify[] = ['success', 'Status updated successfully'];
        return back()->withNotify($notify);
    }
}
