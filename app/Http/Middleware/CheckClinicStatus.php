<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClinicStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->user_type !== 'clinic_admin') {
            return $next($request);
        }

        $clinic = $user->clinic;

        // If no clinic exists, they must do initial setup
        if (!$clinic) {
            if (!$request->routeIs('clinic.subscription') && !$request->routeIs('clinic.setup')) {
                return redirect()->route('clinic.subscription')->with('error', 'Please choose a package to get started.');
            }
            return $next($request);
        }

        // If clinic exists but is not active (package expired or none), redirect to subscription
        if (!$clinic->isActive()) {
            if (!$request->routeIs('clinic.subscription') && !$request->routeIs('clinic.setup')) {
                return redirect()->route('clinic.subscription')->with('error', 'Your subscription is inactive. Please renew to continue.');
            }
        }

        return $next($request);
    }
}
