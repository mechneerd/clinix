<?php

namespace App\Http\Controllers\Api;

use App\Models\Prescription;
use App\Http\Resources\PrescriptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions.
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Prescription::with([
            'medicalRecord.patient',
            'medicalRecord.doctor.user',
            'items.medicine',
        ]);

        if ($user->isPatient()) {
            $query->whereHas('medicalRecord', function ($q) use ($user) {
                $q->where('patient_id', $user->patient->id);
            });
        } elseif ($user->isStaff()) {
            $clinicId = $user->staff->clinic_id;
            $query->whereHas('medicalRecord.doctor', function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });
        } elseif ($user->isClinicAdmin()) {
            $clinicId = $user->clinic->id;
            $query->whereHas('medicalRecord.doctor', function ($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            });
        }

        $query->when($request->is_dispensed !== null, function ($q) use ($request) {
            $q->where('is_dispensed', $request->is_dispensed);
        });

        $query->when($request->search, function ($q, $search) {
            $q->where(function ($query) use ($search) {
                $query->where('prescription_no', 'like', "%{$search}%")
                    ->orWhereHas('medicalRecord.patient', function ($patientQuery) use ($search) {
                        $patientQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('patient_code', 'like', "%{$search}%");
                    });
            });
        });

        $prescriptions = $query->latest()->paginate($request->get('per_page', 15));

        return $this->successResponse(
            PrescriptionResource::collection($prescriptions)->response()->getData(true),
            'Prescriptions retrieved successfully'
        );
    }

    /**
     * Display the specified prescription.
     */
    public function show(Prescription $prescription): JsonResponse
    {
        $prescription->load([
            'medicalRecord.patient',
            'medicalRecord.doctor.user',
            'items.medicine',
            'items.medicineBatch',
        ]);

        return $this->successResponse(
            new PrescriptionResource($prescription),
            'Prescription retrieved successfully'
        );
    }

    /**
     * Dispense the specified prescription.
     */
    public function dispense(Request $request, Prescription $prescription): JsonResponse
    {
        if ($prescription->is_dispensed) {
            return $this->errorResponse('Prescription has already been dispensed', 400);
        }

        $prescription->update([
            'is_dispensed' => true,
            'dispensed_at' => now(),
        ]);

        return $this->successResponse(
            new PrescriptionResource($prescription->load([
                'medicalRecord.patient',
                'medicalRecord.doctor.user',
                'items.medicine',
            ])),
            'Prescription dispensed successfully'
        );
    }
}
