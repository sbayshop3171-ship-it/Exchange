<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use Illuminate\Http\Request;

class BlockedIpController extends Controller
{
    public function blockedIpList()
    {
        $pageTitle = 'Blocked IP List';
        $ips       = BlockedIp::searchable(['ip_address'])->dateFilter()->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.setting.ip_list',  compact('pageTitle', 'ips'));
    }

    public function blockedIpInsert(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|unique:blocked_ips,ip_address|ip'
        ], [
            'ip_address.unique' => 'This IP address already blocked'
        ]);

        $blockedIp             = new BlockedIp();
        $blockedIp->ip_address = $request->ip_address;
        $blockedIp->save();

        $notify[] = ['success', 'IP blocked successfully'];
        return back()->withNotify($notify);
    }

    public function blockedIpDelete(Request $request)
    {
        $blockedIp = BlockedIp::findOrFail($request->id);
        $blockedIp->delete();

        $notify[] = ['success', 'IP unblocked successfully'];
        return back()->withNotify($notify);
    }
}
