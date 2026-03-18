<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedCustom
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->email_verified_at && !$request->routeIs('verify-otp', 'logout')) {
            return redirect()->route('verify-otp');
        }

        return $next($request);
    }
}
