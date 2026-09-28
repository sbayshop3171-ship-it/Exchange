<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $blockedIps = BlockedIp::pluck('ip_address')->toArray();
        $clientIp = $request->ip();

        if (in_array($clientIp, $blockedIps)) {
            return response('Your have been blocked.', Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
