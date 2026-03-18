<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class TenancyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share clinic data with all views for authenticated clinic users
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                if ($user->isClinicAdmin() && $user->clinic) {
                    $view->with('currentClinic', $user->clinic);
                }
                
                if ($user->isStaff() && $user->staff) {
                    $view->with('currentStaff', $user->staff);
                    $view->with('currentClinic', $user->staff->clinic);
                }
            }
        });
    }
}