<?php

namespace App\Http\Controllers\User;

use App\Models\Referral;
use App\Http\Controllers\Controller;

class AffiliateController extends Controller
{
    public function affiliate()
    {
        $pageTitle = "Affiliation";
        $user      = auth()->user()->load('allReferrals');
        $maxLevel  = Referral::max('level');
        return view('Template::user.affiliate.index', compact('pageTitle', 'user', 'maxLevel'));
    }
}
