<?php

namespace App\Http\Controllers\Api;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/patients",
     *     summary="Get all patients",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name, email, or patient code",
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
     *         description="Patients retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Patient::with(['user', 'country']);

        // Filter by clinic if user is staff or clinic admin
        if ($user->isStaff()) {
            $query->whereHas('clinics', fn($q) => $q->where('clinics.id', $user->staff->clinic_id));
        } elseif ($user->isClinicAdmin()) {
            $query->whereHas('clinics', fn($q) => $q->where('clinics.id', $user->clinic->id));
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('patient_code', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($patients, 'Patients retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/patients",
     *     summary="Create a new patient",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name","last_name","phone","date_of_birth","gender"},
     *             @OA\Property(property="first_name", type="string", example="John"),
     *             @OA\Property(property="last_name", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="gender", type="string", enum={"male","female","other"}),
     *             @OA\Property(property="blood_group", type="string", example="A+"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="emergency_contact_name", type="string"),
     *             @OA\Property(property="emergency_contact_phone", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Patient created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $patient = Patient::create($request->all());

        // Attach to clinic if user is staff or clinic admin
        $user = auth()->user();
        if ($user->isStaff()) {
            $patient->clinics()->attach($user->staff->clinic_id, [
                'registered_at' => now(),
                'registration_type' => 'walk_in'
            ]);
        } elseif ($user->isClinicAdmin()) {
            $patient->clinics()->attach($user->clinic->id, [
                'registered_at' => now(),
                'registration_type' => 'walk_in'
            ]);
        }

        $patient->load(['user', 'country']);

        return $this->successResponse($patient, 'Patient created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{id}",
     *     summary="Get a specific patient",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found"
     *     )
     * )
     */
    public function show(Patient $patient): JsonResponse
    {
        $patient->load(['user', 'country', 'clinics', 'allergies']);

        return $this->successResponse($patient, 'Patient retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/patients/{id}",
     *     summary="Update a patient",
     *     tags={"Patients"},
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
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="date_of_birth", type="string", format="date"),
     *             @OA\Property(property="gender", type="string", enum={"male","female","other"}),
     *             @OA\Property(property="blood_group", type="string"),
     *             @OA\Property(property="address", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Patient $patient): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|nullable|email|unique:patients,email,' . $patient->id,
            'phone' => 'sometimes|string|max:20',
            'date_of_birth' => 'sometimes|date|before:today',
            'gender' => 'sometimes|in:male,female,other',
            'blood_group' => 'sometimes|nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'sometimes|nullable|string',
            'emergency_contact_name' => 'sometimes|nullable|string|max:255',
            'emergency_contact_phone' => 'sometimes|nullable|string|max:20',
            'allergies' => 'sometimes|nullable|string',
            'medical_history' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $patient->update($request->all());
        $patient->load(['user', 'country']);

        return $this->successResponse($patient, 'Patient updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/patients/{id}",
     *     summary="Delete a patient",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient deleted successfully"
     *     )
     * )
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $user = auth()->user();

        // Detach from clinic instead of deleting
        if ($user->isStaff()) {
            $patient->clinics()->detach($user->staff->clinic_id);
        } elseif ($user->isClinicAdmin()) {
            $patient->clinics()->detach($user->clinic->id);
        }

        return $this->successResponse(null, 'Patient removed from clinic successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{id}/appointments",
     *     summary="Get patient appointments",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient appointments retrieved successfully"
     *     )
     * )
     */
    public function appointments(Patient $patient): JsonResponse
    {
        $appointments = $patient->appointments()
            ->with(['clinic', 'doctor.user'])
            ->latest()
            ->paginate(15);

        return $this->paginatedResponse($appointments, 'Patient appointments retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{id}/medical-records",
     *     summary="Get patient medical records",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient medical records retrieved successfully"
     *     )
     * )
     */
    public function medicalRecords(Patient $patient): JsonResponse
    {
        $records = $patient->medicalRecords()
            ->with(['doctor.user', 'appointment'])
            ->latest()
            ->paginate(15);

        return $this->paginatedResponse($records, 'Patient medical records retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/patients/{id}/prescriptions",
     *     summary="Get patient prescriptions",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Patient prescriptions retrieved successfully"
     *     )
     * )
     */
    public function prescriptions(Patient $patient): JsonResponse
    {
        $prescriptions = Prescription::whereHas('medicalRecord', function ($query) use ($patient) {
            $query->where('patient_id', $patient->id);
        })
            ->with(['medicalRecord.doctor.user', 'items.medicine'])
            ->latest()
            ->paginate(15);

        return $this->paginatedResponse($prescriptions, 'Patient prescriptions retrieved successfully');
    }
}
