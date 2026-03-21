<?php

namespace App\Http\Controllers\Api;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/medical-records",
     *     summary="Get all medical records",
     *     tags={"Medical Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="query",
     *         description="Filter by patient ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="doctor_id",
     *         in="query",
     *         description="Filter by doctor ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by diagnosis or symptoms",
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
     *         description="Medical records retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = MedicalRecord::with(['patient', 'doctor.user', 'appointment']);

        // Filter based on user type
        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $clinicId = $user->staff->clinic_id;
            $query->whereHas('doctor', function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });
            
            // Doctors can only see their own records
            if ($user->staff->isDoctor()) {
                $query->where('doctor_id', $user->staff->id);
            }
        } elseif ($user->isClinicAdmin()) {
            $clinicId = $user->clinic->id;
            $query->whereHas('doctor', function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });
        }

        // Apply filters
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('diagnosis', 'like', "%{$search}%")
                  ->orWhere('symptoms', 'like', "%{$search}%")
                  ->orWhere('treatment_plan', 'like', "%{$search}%");
            });
        }

        $records = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($records, 'Medical records retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/medical-records",
     *     summary="Create a new medical record",
     *     tags={"Medical Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patient_id","doctor_id","diagnosis"},
     *             @OA\Property(property="appointment_id", type="integer", example=1),
     *             @OA\Property(property="patient_id", type="integer", example=1),
     *             @OA\Property(property="doctor_id", type="integer", example=1),
     *             @OA\Property(property="diagnosis", type="string", example="Common cold"),
     *             @OA\Property(property="symptoms", type="string", example="Fever, cough"),
     *             @OA\Property(property="treatment_plan", type="string", example="Rest and medication"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="attachments", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Medical record created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'nullable|exists:appointments,id',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:staff,id',
            'diagnosis' => 'required|string',
            'symptoms' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        $record = MedicalRecord::create(array_merge(
            $validator->validated(),
            ['created_by' => $user->id]
        ));

        $record->load(['patient', 'doctor.user', 'appointment']);

        return $this->successResponse($record, 'Medical record created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/medical-records/{id}",
     *     summary="Get a specific medical record",
     *     tags={"Medical Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medical record retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Medical record not found"
     *     )
     * )
     */
    public function show(MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->load(['patient', 'doctor.user', 'appointment', 'prescription.items.medicine']);

        return $this->successResponse($medicalRecord, 'Medical record retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/medical-records/{id}",
     *     summary="Update a medical record",
     *     tags={"Medical Records"},
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
     *             @OA\Property(property="diagnosis", type="string"),
     *             @OA\Property(property="symptoms", type="string"),
     *             @OA\Property(property="treatment_plan", type="string"),
     *             @OA\Property(property="notes", type="string"),
     *             @OA\Property(property="attachments", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medical record updated successfully"
     *     )
     * )
     */
    public function update(Request $request, MedicalRecord $medicalRecord): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'diagnosis' => 'sometimes|required|string',
            'symptoms' => 'nullable|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        $medicalRecord->update(array_merge(
            $validator->validated(),
            ['updated_by' => $user->id]
        ));

        $medicalRecord->load(['patient', 'doctor.user', 'appointment']);

        return $this->successResponse($medicalRecord, 'Medical record updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/medical-records/{id}",
     *     summary="Delete a medical record",
     *     tags={"Medical Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Medical record deleted successfully"
     *     )
     * )
     */
    public function destroy(MedicalRecord $medicalRecord): JsonResponse
    {
        $medicalRecord->delete();

        return $this->successResponse(null, 'Medical record deleted successfully');
    }
}
