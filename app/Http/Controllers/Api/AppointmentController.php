<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/appointments",
     *     summary="Get all appointments",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         @OA\Schema(type="string", enum={"pending","scheduled","confirmed","completed","cancelled","no_show"})
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Filter by date (Y-m-d)",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="doctor_id",
     *         in="query",
     *         description="Filter by doctor ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointments retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Appointment::with(['patient', 'doctor.user', 'clinic']);

        // Filter based on user type
        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $query->where('clinic_id', $user->staff->clinic_id);
            
            // Doctors can only see their own appointments
            if ($user->staff->isDoctor()) {
                $query->where('doctor_id', $user->staff->id);
            }
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $appointments = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($appointments, 'Appointments retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/appointments",
     *     summary="Create a new appointment",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patient_id","doctor_id","appointment_date","start_time","end_time","chief_complaint"},
     *             @OA\Property(property="patient_id", type="integer", example=1),
     *             @OA\Property(property="doctor_id", type="integer", example=1),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2026-03-25"),
     *             @OA\Property(property="start_time", type="string", format="time", example="09:00"),
     *             @OA\Property(property="end_time", type="string", format="time", example="09:30"),
     *             @OA\Property(property="type", type="string", enum={"consultation","follow_up","online"}, example="consultation"),
     *             @OA\Property(property="chief_complaint", type="string", example="Regular checkup"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="fee", type="number", format="float", example=50.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:staff,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'sometimes|in:consultation,follow_up,online',
            'chief_complaint' => 'required|string',
            'notes' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        // Check for conflicting appointments
        $conflicting = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($conflicting) {
            return $this->errorResponse('This time slot is already booked', 409);
        }

        $appointment = Appointment::create([
            'clinic_id' => $clinicId,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'type' => $request->get('type', 'consultation'),
            'status' => 'scheduled',
            'chief_complaint' => $request->chief_complaint,
            'notes' => $request->notes,
            'fee' => $request->fee,
        ]);

        $appointment->load(['patient', 'doctor.user', 'clinic']);

        return $this->successResponse($appointment, 'Appointment created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/appointments/{id}",
     *     summary="Get a specific appointment",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment retrieved successfully"
     *     )
     * )
     */
    public function show(Appointment $appointment): JsonResponse
    {
        $appointment->load(['patient', 'doctor.user', 'clinic', 'medicalRecord', 'vitals']);

        return $this->successResponse($appointment, 'Appointment retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/appointments/{id}",
     *     summary="Update an appointment",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="appointment_date", type="string", format="date"),
     *             @OA\Property(property="start_time", type="string", format="time"),
     *             @OA\Property(property="end_time", type="string", format="time"),
     *             @OA\Property(property="type", type="string"),
     *             @OA\Property(property="chief_complaint", type="string"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="fee", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Appointment $appointment): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'appointment_date' => 'sometimes|date|after_or_equal:today',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'type' => 'sometimes|in:consultation,follow_up,online',
            'chief_complaint' => 'sometimes|string',
            'notes' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $appointment->update($request->all());
        $appointment->load(['patient', 'doctor.user', 'clinic']);

        return $this->successResponse($appointment, 'Appointment updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/appointments/{id}",
     *     summary="Delete an appointment",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment deleted successfully"
     *     )
     * )
     */
    public function destroy(Appointment $appointment): JsonResponse
    {
        $appointment->delete();

        return $this->successResponse(null, 'Appointment deleted successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/appointments/{id}/check-in",
     *     summary="Check in a patient",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient checked in successfully"
     *     )
     * )
     */
    public function checkIn(Appointment $appointment): JsonResponse
    {
        if ($appointment->status !== 'scheduled') {
            return $this->errorResponse('Appointment cannot be checked in', 400);
        }

        $appointment->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        return $this->successResponse($appointment, 'Patient checked in successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/appointments/{id}/complete",
     *     summary="Complete an appointment",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment completed successfully"
     *     )
     * )
     */
    public function complete(Appointment $appointment): JsonResponse
    {
        if (!in_array($appointment->status, ['scheduled', 'confirmed', 'checked_in'])) {
            return $this->errorResponse('Appointment cannot be completed', 400);
        }

        $appointment->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return $this->successResponse($appointment, 'Appointment completed successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/appointments/{id}/cancel",
     *     summary="Cancel an appointment",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Appointment cancelled successfully"
     *     )
     * )
     */
    public function cancel(Appointment $appointment): JsonResponse
    {
        if (in_array($appointment->status, ['completed', 'cancelled'])) {
            return $this->errorResponse('Appointment cannot be cancelled', 400);
        }

        $appointment->update(['status' => 'cancelled']);

        return $this->successResponse($appointment, 'Appointment cancelled successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/appointments/today",
     *     summary="Get today's appointments",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Today's appointments retrieved successfully"
     *     )
     * )
     */
    public function today(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Appointment::with(['patient', 'doctor.user'])
            ->whereDate('appointment_date', today());

        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $query->where('clinic_id', $user->staff->clinic_id);
            if ($user->staff->isDoctor()) {
                $query->where('doctor_id', $user->staff->id);
            }
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        $appointments = $query->orderBy('start_time')->get();

        return $this->successResponse($appointments, 'Today\'s appointments retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/appointments/upcoming",
     *     summary="Get upcoming appointments",
     *     tags={"Appointments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Upcoming appointments retrieved successfully"
     *     )
     * )
     */
    public function upcoming(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Appointment::with(['patient', 'doctor.user', 'clinic'])
            ->where('appointment_date', '>=', today())
            ->whereIn('status', ['scheduled', 'confirmed']);

        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $query->where('clinic_id', $user->staff->clinic_id);
            if ($user->staff->isDoctor()) {
                $query->where('doctor_id', $user->staff->id);
            }
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        $appointments = $query->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return $this->successResponse($appointments, 'Upcoming appointments retrieved successfully');
    }
}
