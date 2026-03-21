<?php

namespace App\Http\Controllers\Api;

use App\Models\Vital;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class VitalController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/vitals",
     *     summary="Get all vitals",
     *     tags={"Vitals"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="query",
     *         description="Filter by patient ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="appointment_id",
     *         in="query",
     *         description="Filter by appointment ID",
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
     *         description="Vitals retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Vital::with(['patient', 'appointment', 'recorder']);

        // Filter based on user type
        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $clinicId = $user->staff->clinic_id;
            $query->whereHas('patient.clinics', function ($q) use ($clinicId) {
                $q->where('clinics.id', $clinicId);
            });
        } elseif ($user->isClinicAdmin()) {
            $clinicId = $user->clinic->id;
            $query->whereHas('patient.clinics', function ($q) use ($clinicId) {
                $q->where('clinics.id', $clinicId);
            });
        }

        // Apply filters
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('appointment_id')) {
            $query->where('appointment_id', $request->appointment_id);
        }

        $vitals = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($vitals, 'Vitals retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/vitals",
     *     summary="Create a new vital record",
     *     tags={"Vitals"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patient_id"},
     *             @OA\Property(property="patient_id", type="integer", example=1),
     *             @OA\Property(property="appointment_id", type="integer", example=1),
     *             @OA\Property(property="blood_pressure", type="string", example="120/80"),
     *             @OA\Property(property="temperature", type="number", format="float", example=98.6),
     *             @OA\Property(property="pulse", type="integer", example=72),
     *             @OA\Property(property="weight", type="number", format="float", example=70.5),
     *             @OA\Property(property="height", type="number", format="float", example=175.0),
     *             @OA\Property(property="respiratory_rate", type="integer", example=16),
     *             @OA\Property(property="oxygen_saturation", type="number", format="float", example=98.0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vital record created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'blood_pressure' => 'nullable|string|max:20',
            'temperature' => 'nullable|numeric|min:90|max:110',
            'pulse' => 'nullable|integer|min:30|max:250',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        // Calculate BMI if weight and height are provided
        $data = $validator->validated();
        if (isset($data['weight']) && isset($data['height']) && $data['height'] > 0) {
            $heightInMeters = $data['height'] / 100;
            $data['bmi'] = round($data['weight'] / ($heightInMeters * $heightInMeters), 2);
        }

        $vital = Vital::create(array_merge($data, [
            'recorded_by' => $user->id,
        ]));

        $vital->load(['patient', 'appointment', 'recorder']);

        return $this->successResponse($vital, 'Vital record created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/vitals/{id}",
     *     summary="Get a specific vital record",
     *     tags={"Vitals"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vital record retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vital record not found"
     *     )
     * )
     */
    public function show(Vital $vital): JsonResponse
    {
        $vital->load(['patient', 'appointment', 'recorder']);

        return $this->successResponse($vital, 'Vital record retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/vitals/{id}",
     *     summary="Update a vital record",
     *     tags={"Vitals"},
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
     *             @OA\Property(property="blood_pressure", type="string"),
     *             @OA\Property(property="temperature", type="number"),
     *             @OA\Property(property="pulse", type="integer"),
     *             @OA\Property(property="weight", type="number"),
     *             @OA\Property(property="height", type="number"),
     *             @OA\Property(property="respiratory_rate", type="integer"),
     *             @OA\Property(property="oxygen_saturation", type="number")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vital record updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Vital $vital): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'blood_pressure' => 'nullable|string|max:20',
            'temperature' => 'nullable|numeric|min:90|max:110',
            'pulse' => 'nullable|integer|min:30|max:250',
            'weight' => 'nullable|numeric|min:0|max:500',
            'height' => 'nullable|numeric|min:0|max:300',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $data = $validator->validated();
        
        // Calculate BMI if weight and height are provided
        if (isset($data['weight']) && isset($data['height']) && $data['height'] > 0) {
            $heightInMeters = $data['height'] / 100;
            $data['bmi'] = round($data['weight'] / ($heightInMeters * $heightInMeters), 2);
        }

        $vital->update($data);
        $vital->load(['patient', 'appointment', 'recorder']);

        return $this->successResponse($vital, 'Vital record updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/vitals/{id}",
     *     summary="Delete a vital record",
     *     tags={"Vitals"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vital record deleted successfully"
     *     )
     * )
     */
    public function destroy(Vital $vital): JsonResponse
    {
        $vital->delete();

        return $this->successResponse(null, 'Vital record deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{patient}/vitals",
     *     summary="Get vitals for a specific patient",
     *     tags={"Vitals"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient vitals retrieved successfully"
     *     )
     * )
     */
    public function patientVitals(Patient $patient): JsonResponse
    {
        $vitals = $patient->vitals()
            ->with(['appointment', 'recorder'])
            ->latest()
            ->paginate(15);

        return $this->paginatedResponse($vitals, 'Patient vitals retrieved successfully');
    }
}
