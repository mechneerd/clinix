<?php

namespace App\Http\Controllers\Api;

use App\Models\Clinic;
use App\Http\Resources\ClinicResource;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClinicController extends Controller
{
    /**
     * Display a listing of clinics.
     */
    public function index(Request $request): JsonResponse
    {
        $clinics = Clinic::where('status', 'active')
            ->with(['admin', 'package'])
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(
            ClinicResource::collection($clinics)->response()->getData(true),
            'Clinics retrieved successfully'
        );
    }

    /**
     * Display the specified clinic.
     */
    public function show(Clinic $clinic): JsonResponse
    {
        $clinic->load(['admin', 'package', 'departments']);

        return $this->successResponse(
            new ClinicResource($clinic),
            'Clinic retrieved successfully'
        );
    }

    /**
     * Get doctors for a specific clinic.
     */
    public function doctors(Clinic $clinic, Request $request): JsonResponse
    {
        $doctors = $clinic->staff()
            ->where('role', 'doctor')
            ->where('is_active', true)
            ->with(['user', 'department'])
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(
            DoctorResource::collection($doctors)->response()->getData(true),
            'Clinic doctors retrieved successfully'
        );
    }

    /**
     * Get departments for a specific clinic.
     */
    public function departments(Clinic $clinic): JsonResponse
    {
        $departments = $clinic->departments()
            ->where('is_active', true)
            ->withCount('staff')
            ->get();

        return $this->successResponse(
            DepartmentResource::collection($departments),
            'Clinic departments retrieved successfully'
        );
    }
}
