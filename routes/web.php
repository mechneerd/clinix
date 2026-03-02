<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RegisterAdmin;
use App\Livewire\Auth\RegisterPatient;
use App\Livewire\Subscription\PackageSelection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ─── Root Redirect ────────────────────────────────────────────────────────────
// Route::get('/', function () {
//     return view('landing.clinix');
// });
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return $user->hasActiveSubscription()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('subscription.select');
        }
        return redirect()->route('patient.dashboard');
    }
    return redirect()->route('login');
});

// ─── Guest Routes ─────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', fn () => view('livewire.auth.register-type'))->name('register');
    Route::get('/register/provider', RegisterAdmin::class)->name('register.admin');
    Route::get('/register/patient', RegisterPatient::class)->name('register.patient');
});

// ─── Authenticated Routes ─────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    Route::post('/logout', function () {
        app(\App\Services\AuthService::class)->logout();
        return redirect()->route('login');
    })->name('logout');

    // Subscription (admin only)
    Route::get('/subscription/select', PackageSelection::class)
        ->name('subscription.select')
        ->middleware('admin');

    // Super Admin
    Route::prefix('super-admin')->middleware('super_admin')->group(function () {
        Route::get('/dashboard', \App\Livewire\SuperAdmin\Dashboard::class)->name('super-admin.dashboard');
    });

    // Admin
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');

        // Clinics
        Route::get('/clinics',        \App\Livewire\Admin\Clinics\Index::class)->name('admin.clinics.index');
        Route::get('/clinics/create', \App\Livewire\Admin\Clinics\Create::class)->name('admin.clinics.create');
        Route::get('/clinics/{id}',   \App\Livewire\Admin\Clinics\Show::class)->name('admin.clinics.show');

        // Departments
        Route::get('/clinics/{clinicId}/departments', \App\Livewire\Admin\Departments\Index::class)->name('admin.departments.index');

        // Staff
        Route::get('/clinics/{clinicId}/staff',     \App\Livewire\Admin\Staff\Index::class)->name('admin.staff.index');
        Route::get('/clinics/{clinicId}/staff/add', \App\Livewire\Admin\Staff\Add::class)->name('admin.staff.add');

        // Labs
        Route::get('/clinics/{clinicId}/labs',                   \App\Livewire\Admin\Labs\Index::class)->name('admin.labs.index');
        Route::get('/clinics/{clinicId}/labs/{labId}/tests',     \App\Livewire\Admin\Labs\Tests::class)->name('admin.labs.tests');

        // Pharmacies
        Route::get('/clinics/{clinicId}/pharmacies',                        \App\Livewire\Admin\Pharmacies\Index::class)->name('admin.pharmacies.index');
        Route::get('/clinics/{clinicId}/pharmacies/{pharmacyId}/medicines', \App\Livewire\Admin\Pharmacies\Medicines::class)->name('admin.pharmacies.medicines');
    });

    // Patient
    Route::prefix('patient')->middleware('patient')->group(function () {
        Route::get('/dashboard',        \App\Livewire\Patient\Dashboard::class)->name('patient.dashboard');
        Route::get('/appointments',     \App\Livewire\Patient\Appointments::class)->name('patient.appointments');
        Route::get('/book-appointment', \App\Livewire\Patient\BookAppointment::class)->name('patient.book-appointment');
        Route::get('/lab-orders',       \App\Livewire\Patient\LabOrders::class)->name('patient.lab-orders');
        Route::get('/prescriptions',    \App\Livewire\Patient\Prescriptions::class)->name('patient.prescriptions');
        Route::get('/reports',          \App\Livewire\Patient\Reports::class)->name('patient.reports');
    });

});

