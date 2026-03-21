<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\ClinicController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\LabTestController;
use App\Http\Controllers\Api\PrescriptionController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\VitalController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\PatientAdmissionController;
use App\Http\Controllers\Api\LabOrderController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\LabConsumableController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });

    // Patient routes
    Route::apiResource('patients', PatientController::class);
    Route::get('patients/{patient}/appointments', [PatientController::class, 'appointments']);
    Route::get('patients/{patient}/medical-records', [PatientController::class, 'medicalRecords']);
    Route::get('patients/{patient}/prescriptions', [PatientController::class, 'prescriptions']);

    // Appointment routes
    Route::apiResource('appointments', AppointmentController::class);
    Route::post('appointments/{appointment}/check-in', [AppointmentController::class, 'checkIn']);
    Route::post('appointments/{appointment}/complete', [AppointmentController::class, 'complete']);
    Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    Route::get('appointments/today', [AppointmentController::class, 'today']);
    Route::get('appointments/upcoming', [AppointmentController::class, 'upcoming']);

    // Doctor routes
    Route::apiResource('doctors', DoctorController::class);
    Route::get('doctors/{doctor}/appointments', [DoctorController::class, 'appointments']);
    Route::get('doctors/{doctor}/schedule', [DoctorController::class, 'schedule']);
    Route::get('doctors/{doctor}/patients', [DoctorController::class, 'patients']);

    // Clinic routes
    Route::apiResource('clinics', ClinicController::class)->only(['index', 'show']);
    Route::get('clinics/{clinic}/doctors', [ClinicController::class, 'doctors']);
    Route::get('clinics/{clinic}/departments', [ClinicController::class, 'departments']);

    // Medicine routes
    Route::apiResource('medicines', MedicineController::class);
    Route::get('medicines/low-stock', [MedicineController::class, 'lowStock']);

    // Lab Test routes
    Route::apiResource('lab-tests', LabTestController::class);

    // Prescription routes
    Route::apiResource('prescriptions', PrescriptionController::class)->only(['index', 'show']);
    Route::post('prescriptions/{prescription}/dispense', [PrescriptionController::class, 'dispense']);

    // Medical Record routes
    Route::apiResource('medical-records', MedicalRecordController::class);

    // Vital routes
    Route::apiResource('vitals', VitalController::class);
    Route::get('patients/{patient}/vitals', [VitalController::class, 'patientVitals']);

    // Staff routes
    Route::apiResource('staff', StaffController::class);

    // Department routes
    Route::apiResource('departments', DepartmentController::class);

    // Admission routes
    Route::apiResource('admissions', PatientAdmissionController::class);
    Route::post('admissions/{admission}/discharge', [PatientAdmissionController::class, 'discharge']);

    // Lab Order routes
    Route::apiResource('lab-orders', LabOrderController::class);

    // Invoice routes
    Route::apiResource('invoices', InvoiceController::class)->only(['index', 'show']);
    Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'pay']);

    // Notification routes
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);

    // Message routes
    Route::get('conversations', [MessageController::class, 'conversations']);
    Route::post('conversations', [MessageController::class, 'createConversation']);
    Route::get('conversations/{conversation}/messages', [MessageController::class, 'messages']);
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'sendMessage']);
    Route::post('messages/{message}/read', [MessageController::class, 'markAsRead']);

    // Lab Consumable routes
    Route::apiResource('lab-consumables', LabConsumableController::class);
    Route::get('lab-consumables/low-stock', [LabConsumableController::class, 'lowStock']);

    // Dashboard stats
    Route::get('dashboard/stats', function () {
        $user = auth()->user();
        
        if ($user->isPatient()) {
            $patient = $user->patient;
            return response()->json([
                'upcoming_appointments' => $patient->appointments()
                    ->where('appointment_date', '>=', today())
                    ->whereIn('status', ['scheduled', 'confirmed'])
                    ->count(),
                'total_visits' => $patient->appointments()->where('status', 'completed')->count(),
                'active_prescriptions' => $patient->prescriptions()
                    ->whereHas('prescription', fn($q) => $q->where('is_dispensed', false))
                    ->count(),
            ]);
        }
        
        if ($user->isStaff()) {
            $clinic = $user->staff->clinic;
            return response()->json([
                'today_appointments' => $clinic->appointments()->today()->count(),
                'pending_appointments' => $clinic->appointments()->where('status', 'scheduled')->count(),
                'total_patients' => $clinic->patients()->count(),
                'total_staff' => $clinic->staff()->count(),
            ]);
        }
        
        return response()->json(['message' => 'Dashboard stats not available'], 403);
    });
});
