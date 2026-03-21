<?php

namespace App\Http\Controllers\Api;

use App\Models\Staff;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/doctors",
     *     summary="Get all doctors",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="clinic_id",
     *         in="query",
     *         description="Filter by clinic ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="Filter by department ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="specialty",
     *         in="query",
     *         description="Filter by specialty",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or employee ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctors retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Staff::with(['user', 'clinic', 'department'])
            ->where('role', 'doctor')
            ->where('is_active', true);

        // Filter by clinic
        if ($request->has('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        } elseif ($user->isStaff()) {
            $query->where('clinic_id', $user->staff->clinic_id);
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        // Filter by department
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $doctors = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($doctors, 'Doctors retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}",
     *     summary="Get a specific doctor",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor retrieved successfully"
     *     )
     * )
     */
    public function show(Staff $doctor): JsonResponse
    {
        $doctor->load(['user', 'clinic', 'department', 'schedules']);

        return $this->successResponse($doctor, 'Doctor retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}/appointments",
     *     summary="Get doctor appointments",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter by date (Y-m-d)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor appointments retrieved successfully"
     *     )
     * )
     */
    public function appointments(Request $request, Staff $doctor): JsonResponse
    {
        $query = $doctor->appointments()
            ->with(['patient', 'clinic']);

        if ($request->has('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest()->paginate(15);

        return $this->paginatedResponse($appointments, 'Doctor appointments retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}/schedule",
     *     summary="Get doctor schedule",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor schedule retrieved successfully"
     *     )
     * )
     */
    public function schedule(Staff $doctor): JsonResponse
    {
        $schedules = $doctor->schedules()
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->get();

        return $this->successResponse($schedules, 'Doctor schedule retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/doctors/{id}/patients",
     *     summary="Get doctor's patients",
     *     tags={"Doctors"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Doctor patients retrieved successfully"
     *     )
     * )
     */
    public function patients(Staff $doctor): JsonResponse
    {
        $patients = $doctor->appointments()
            ->with('patient')
            ->get()
            ->pluck('patient')
            ->unique('id')
            ->values();

        return $this->successResponse($patients, 'Doctor patients retrieved successfully');
    }
}
