<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModule
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = auth()->user();

        // 1. If not logged in, proceed (other middleware handles auth)
        if (!$user) {
            return $next($request);
        }

        // 2. Super Admins bypass module checks
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // 3. Check if user belongs to a clinic
        $clinic = $user->clinic;
        
        // If it's a staff member, they also belong to a clinic
        if (!$clinic && $user->staff) {
            $clinic = $user->staff->clinic;
        }

        if (!$clinic) {
            // If they don't belong to a clinic but are trying to access a clinical module
            return $next($request);
        }

        // 4. Perform the module check
        if (!$clinic->isModuleEnabled($module)) {
            abort(403, 'This module is not enabled for your clinic.');
        }

        return $next($request);
    }
}
