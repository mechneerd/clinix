<?php

namespace App\Http\Controllers\Api;

use App\Models\LabTest;
use App\Http\Resources\LabTestResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LabTestController extends Controller
{
    /**
     * Display a listing of lab tests.
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $labTests = LabTest::where('clinic_id', $clinicId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($request->is_active !== null, function ($query) use ($request) {
                $query->where('is_active', $request->is_active);
            })
            ->orderBy('name')
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(
            LabTestResource::collection($labTests)->response()->getData(true),
            'Lab tests retrieved successfully'
        );
    }

    /**
     * Store a newly created lab test.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:lab_tests,code',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'normal_range' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $labTest = LabTest::create(array_merge(
            $validator->validated(),
            ['clinic_id' => $clinicId]
        ));

        return $this->successResponse(
            new LabTestResource($labTest),
            'Lab test created successfully',
            201
        );
    }

    /**
     * Display the specified lab test.
     */
    public function show(LabTest $labTest): JsonResponse
    {
        return $this->successResponse(
            new LabTestResource($labTest),
            'Lab test retrieved successfully'
        );
    }

    /**
     * Update the specified lab test.
     */
    public function update(Request $request, LabTest $labTest): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50|unique:lab_tests,code,' . $labTest->id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'normal_range' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $labTest->update($validator->validated());

        return $this->successResponse(
            new LabTestResource($labTest),
            'Lab test updated successfully'
        );
    }

    /**
     * Remove the specified lab test.
     */
    public function destroy(LabTest $labTest): JsonResponse
    {
        $labTest->delete();

        return $this->successResponse(null, 'Lab test deleted successfully');
    }
}
