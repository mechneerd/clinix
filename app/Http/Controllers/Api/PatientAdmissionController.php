<?php

namespace App\Http\Controllers\Api;

use App\Models\PatientAdmission;
use App\Models\Room;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PatientAdmissionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admissions",
     *     summary="Get all patient admissions",
     *     tags={"Admissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="query",
     *         description="Filter by patient ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         @OA\Schema(type="string", enum={"admitted","discharged"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admissions retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = PatientAdmission::with(['patient', 'room.ward', 'bed', 'admittedBy']);

        // Filter based on user type
        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $clinicId = $user->staff->clinic_id;
            $query->whereHas('room', function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });
        } elseif ($user->isClinicAdmin()) {
            $clinicId = $user->clinic->id;
            $query->whereHas('room', function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });
        }

        // Apply filters
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $admissions = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($admissions, 'Admissions retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/admissions",
     *     summary="Admit a patient",
     *     tags={"Admissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patient_id","room_id","reason"},
     *             @OA\Property(property="patient_id", type="integer", example=1),
     *             @OA\Property(property="room_id", type="integer", example=1),
     *             @OA\Property(property="bed_id", type="integer", example=1),
     *             @OA\Property(property="reason", type="string", example="Post-surgery recovery"),
     *             @OA\Property(property="admitted_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Patient admitted successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required|exists:rooms,id',
            'bed_id' => 'nullable|exists:beds,id',
            'reason' => 'required|string',
            'admitted_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        // Check if bed is available
        if ($request->bed_id) {
            $bed = Bed::find($request->bed_id);
            if ($bed && $bed->status !== 'available') {
                return $this->errorResponse('Selected bed is not available', 400);
            }
        }

        $admission = PatientAdmission::create([
            'patient_id' => $request->patient_id,
            'room_id' => $request->room_id,
            'bed_id' => $request->bed_id,
            'reason' => $request->reason,
            'admitted_at' => $request->admitted_at ?? now(),
            'status' => 'admitted',
            'admitted_by' => $user->id,
        ]);

        // Update bed status
        if ($request->bed_id) {
            Bed::find($request->bed_id)->update(['status' => 'occupied']);
        }

        // Update room status
        Room::find($request->room_id)->update(['is_occupied' => true]);

        $admission->load(['patient', 'room.ward', 'bed', 'admittedBy']);

        return $this->successResponse($admission, 'Patient admitted successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/admissions/{id}",
     *     summary="Get a specific admission",
     *     tags={"Admissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admission retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Admission not found"
     *     )
     * )
     */
    public function show(PatientAdmission $admission): JsonResponse
    {
        $admission->load(['patient', 'room.ward', 'bed', 'admittedBy']);

        return $this->successResponse($admission, 'Admission retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/admissions/{id}/discharge",
     *     summary="Discharge a patient",
     *     tags={"Admissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient discharged successfully"
     *     )
     * )
     */
    public function discharge(PatientAdmission $admission): JsonResponse
    {
        if ($admission->status === 'discharged') {
            return $this->errorResponse('Patient is already discharged', 400);
        }

        $admission->update([
            'discharged_at' => now(),
            'status' => 'discharged',
        ]);

        // Update bed status
        if ($admission->bed_id) {
            Bed::find($admission->bed_id)->update(['status' => 'available']);
        }

        // Check if room has other active admissions
        $otherOccupied = PatientAdmission::where('room_id', $admission->room_id)
            ->where('status', 'admitted')
            ->exists();

        if (!$otherOccupied) {
            Room::find($admission->room_id)->update(['is_occupied' => false]);
        }

        $admission->load(['patient', 'room.ward', 'bed', 'admittedBy']);

        return $this->successResponse($admission, 'Patient discharged successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/admissions/{id}",
     *     summary="Delete an admission record",
     *     tags={"Admissions"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Admission deleted successfully"
     *     )
     * )
     */
    public function destroy(PatientAdmission $admission): JsonResponse
    {
        // Update bed status if admission was active
        if ($admission->status === 'admitted' && $admission->bed_id) {
            Bed::find($admission->bed_id)->update(['status' => 'available']);
        }

        $admission->delete();

        return $this->successResponse(null, 'Admission deleted successfully');
    }
}
