<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\LandingPage;
use App\Livewire\SuperAdmin\Dashboard as SuperAdminDashboard;
use App\Livewire\SuperAdmin\Packages;
use App\Livewire\SuperAdmin\Clinics;
use App\Livewire\Clinic\Dashboard as ClinicDashboard;
use App\Livewire\Clinic\Settings\Subscription as ClinicSubscription;
use App\Livewire\Clinic\Settings\ClinicSetup;
use App\Livewire\Doctor\Dashboard as DoctorDashboard;
use App\Livewire\Nurse\Dashboard as NurseDashboard;
use App\Livewire\Lab\Dashboard as LabDashboard;
use App\Livewire\Pharmacy\Dashboard as PharmacyDashboard;
use App\Livewire\Reception\Dashboard as ReceptionDashboard;
use App\Livewire\Patient\Dashboard as PatientDashboard;

// Public Routes
Route::get('/', LandingPage::class)->name('home');
Route::get('/login', LoginPage::class)->name('login');
Route::get('/register', RegisterPage::class)->name('register');
Route::get('/verify-otp', \App\Livewire\Auth\VerifyOtp::class)->name('verify-otp');

// Social Auth
Route::get('/auth/google', [App\Http\Controllers\Auth\SocialController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\SocialController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Super Admin Routes
    Route::middleware(['role:super-admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
            Route::get('/dashboard', SuperAdminDashboard::class)->name('dashboard');
            Route::get('/packages', Packages::class)->name('packages');
            Route::get('/packages/create', Packages::class)->name('packages.create');
            Route::get('/clinics', Clinics::class)->name('clinics');
            Route::get('/clinics/create', Clinics::class)->name('clinics.create');
            Route::get('/admin/{user}/modules', \App\Livewire\SuperAdmin\AdminModules::class)->name('admin.modules');
            Route::get('/modules', \App\Livewire\SuperAdmin\Modules::class)->name('modules');
            Route::get('/subscriptions', SuperAdminDashboard::class)->name('subscriptions');
            Route::get('/analytics', SuperAdminDashboard::class)->name('analytics');
            Route::get('/settings', SuperAdminDashboard::class)->name('settings');
            Route::get('/settings/locations', \App\Livewire\Admin\Settings\LocationManager::class)->name('settings.locations');
        }
        );

        // Clinic Admin Routes
        Route::middleware(['role:clinic-admin'])->prefix('clinic')->name('clinic.')->group(function () {
            Route::get('/dashboard', ClinicDashboard::class)->name('dashboard');
            Route::get('/calendar', \App\Livewire\Clinic\Calendar::class)->name('calendar');
            Route::get('/settings/subscription', ClinicSubscription::class)->name('subscription');
            Route::get('/settings/clinic', ClinicSetup::class)->name('clinic-setup');
            Route::get('/settings', ClinicDashboard::class)->name('settings');
            Route::get('/settings/locations', \App\Livewire\Admin\Settings\LocationManager::class)->name('settings.locations');

            // Operational Routes - Protected by clinic_status
            Route::middleware(['clinic_status'])->group(function () {
                Route::group(['middleware' => 'module:departments'], function () {
                    Route::get('/departments', \App\Livewire\Clinic\Departments::class)->name('departments');
                });
                
                Route::group(['middleware' => 'module:staff'], function () {
                    Route::get('/doctors', \App\Livewire\Clinic\Doctors::class)->name('doctors');
                    Route::get('/staff', \App\Livewire\Clinic\StaffMembers::class)->name('staff');
                });

                Route::group(['middleware' => 'module:medicines'], function () {
                    Route::get('/medicines', \App\Livewire\Clinic\Medicines::class)->name('medicines');
                    Route::get('/inventory/audit', \App\Livewire\Clinic\InventoryAudit::class)->name('inventory-audit');
                });

                Route::group(['middleware' => 'module:laboratory'], function () {
                    Route::get('/lab-tests', \App\Livewire\Clinic\LabTests::class)->name('lab-tests');
                    Route::get('/lab-consumables', \App\Livewire\Clinic\LabConsumables::class)->name('lab-consumables');
                });

                Route::group(['middleware' => 'module:patients'], function () {
                    Route::get('/patients', \App\Livewire\Clinic\Patients::class)->name('patients');
                    Route::get('/patients/create', \App\Livewire\Clinic\Patients::class)->name('patients.create');
                });

                Route::group(['middleware' => 'module:appointments'], function () {
                    Route::get('/appointments', \App\Livewire\Clinic\Appointments::class)->name('appointments');
                    Route::get('/appointments/create', \App\Livewire\Clinic\Appointments::class)->name('appointments.create');
                });

                Route::group(['middleware' => 'module:billing'], function () {
                    Route::get('/billing', ClinicDashboard::class)->name('billing');
                });

                Route::group(['middleware' => 'module:reports'], function () {
                    Route::get('/reports', ClinicDashboard::class)->name('reports');
                });

                Route::get('/admissions', \App\Livewire\Clinic\Admissions::class)->name('admissions');
                Route::get('/workforce', \App\Livewire\Clinic\Workforce::class)->name('workforce');
                Route::get('/ledger', \App\Livewire\Clinic\Ledger::class)->name('ledger');
                Route::get('/compliance', \App\Livewire\Clinic\Compliance::class)->name('compliance');
                Route::get('/settings/configuration', \App\Livewire\Clinic\Settings\Configuration::class)->name('clinic-configuration');
            });
        });

        // Doctor Routes
        Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
            Route::get('/dashboard', DoctorDashboard::class)->name('dashboard');
            Route::get('/calendar', \App\Livewire\Doctor\Calendar::class)->name('calendar');
            Route::get('/appointments', DoctorDashboard::class)->name('appointments');
            Route::get('/patients', DoctorDashboard::class)->name('patients');
            Route::get('/medicines', DoctorDashboard::class)->name('medicines');
            Route::get('/lab-tests', DoctorDashboard::class)->name('lab-tests');
            Route::get('/prescriptions', DoctorDashboard::class)->name('prescriptions');
            Route::get('/lab-requests', DoctorDashboard::class)->name('lab-requests');
            Route::get('/medical-records', DoctorDashboard::class)->name('medical-records');
            Route::get('/schedule', DoctorDashboard::class)->name('schedule');
            Route::get('/consultation/{appointment}', \App\Livewire\Doctor\ClinicalEncounter::class)->name('consultation');
        });

        // Nurse Routes
        Route::middleware(['role:nurse'])->prefix('nurse')->name('nurse.')->group(function () {
            Route::get('/dashboard', NurseDashboard::class)->name('dashboard');
            Route::get('/appointments', NurseDashboard::class)->name('appointments');
            Route::get('/patients', NurseDashboard::class)->name('patients');
            Route::get('/vitals', NurseDashboard::class)->name('vitals');
            Route::get('/tasks', NurseDashboard::class)->name('tasks');
        }
        );

        // Lab Routes (Manager & Worker)
        Route::middleware(['role:lab_manager|lab_worker'])->prefix('lab')->name('lab.')->group(function () {
            Route::get('/dashboard', LabDashboard::class)->name('dashboard');
            Route::get('/tests', LabDashboard::class)->name('tests');
            Route::get('/orders', LabDashboard::class)->name('orders');
            Route::get('/results', LabDashboard::class)->name('results');
            Route::get('/inventory', LabDashboard::class)->name('inventory');
        }
        );

        // Pharmacy Routes (Manager & Worker)
        Route::middleware(['role:pharmacy_manager|pharmacy_worker'])->prefix('pharmacy')->name('pharmacy.')->group(function () {
            Route::get('/dashboard', PharmacyDashboard::class)->name('dashboard');
            Route::get('/medicines', PharmacyDashboard::class)->name('medicines');
            Route::get('/prescriptions', PharmacyDashboard::class)->name('prescriptions');
            Route::get('/inventory', PharmacyDashboard::class)->name('inventory');
            Route::get('/sales', PharmacyDashboard::class)->name('sales');
        }
        );

        // Reception Routes (Manager & Receptionist)
        Route::middleware(['role:reception_manager|receptionist'])->prefix('reception')->name('reception.')->group(function () {
            Route::get('/dashboard', ReceptionDashboard::class)->name('dashboard');
            Route::get('/appointments', ReceptionDashboard::class)->name('appointments');
            Route::get('/patients', ReceptionDashboard::class)->name('patients');
            Route::get('/check-in', ReceptionDashboard::class)->name('check-in');
            Route::get('/billing', ReceptionDashboard::class)->name('billing');
            Route::get('/queue', ReceptionDashboard::class)->name('queue');
        }
        );

        // Patient Routes
        Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
            Route::get('/dashboard', PatientDashboard::class)->name('dashboard');
            Route::get('/calendar', \App\Livewire\Patient\Calendar::class)->name('calendar');
            Route::get('/browse-clinics', \App\Livewire\Patient\BrowseClinics::class)->name('browse-clinics');
            Route::get('/appointments', PatientDashboard::class)->name('appointments');
            Route::get('/prescriptions', PatientDashboard::class)->name('prescriptions');
            Route::get('/lab-reports', PatientDashboard::class)->name('lab-reports');
            Route::get('/medical-history', PatientDashboard::class)->name('medical-history');
            Route::get('/billing', PatientDashboard::class)->name('billing');
            Route::get('/book-appointment/{clinic_slug?}', \App\Livewire\Patient\BookAppointment::class)->name('book-appointment');
            Route::get('/complete-profile', \App\Livewire\Patient\CompleteProfile::class)->name('complete-profile');
        }
        );

        // Common Routes
        Route::get('/messages/{conversationId?}', \App\Livewire\Chat\ChatHub::class)->name('messages');

        Route::get('/profile', function () {
            return view('profile');
        })->name('profile');

        Route::get('/settings', function () {
            return view('profile');
        })->name('shared.settings');    });

// Logout
Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('home');
})->name('logout');