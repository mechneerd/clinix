<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use App\Http\Resources\MedicineResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{
    /**
     * Display a listing of medicines.
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $medicines = Medicine::where('clinic_id', $clinicId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('generic_name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->when($request->is_active !== null, function ($query) use ($request) {
                $query->where('is_active', $request->is_active);
            })
            ->with(['batches' => function ($query) {
                $query->where('expiry_date', '>', now())
                    ->where('current_quantity', '>', 0);
            }])
            ->orderBy('name')
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(
            MedicineResource::collection($medicines)->response()->getData(true),
            'Medicines retrieved successfully'
        );
    }

    /**
     * Store a newly created medicine.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'dosage_form' => 'required|string|max:100',
            'strength' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $medicine = Medicine::create(array_merge(
            $validator->validated(),
            ['clinic_id' => $clinicId]
        ));

        return $this->successResponse(
            new MedicineResource($medicine->load('batches')),
            'Medicine created successfully',
            201
        );
    }

    /**
     * Display the specified medicine.
     */
    public function show(Medicine $medicine): JsonResponse
    {
        $medicine->load(['batches', 'clinic']);

        return $this->successResponse(
            new MedicineResource($medicine),
            'Medicine retrieved successfully'
        );
    }

    /**
     * Update the specified medicine.
     */
    public function update(Request $request, Medicine $medicine): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'sometimes|required|string|max:100',
            'dosage_form' => 'sometimes|required|string|max:100',
            'strength' => 'nullable|string|max:100',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'reorder_level' => 'sometimes|required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $medicine->update($validator->validated());

        return $this->successResponse(
            new MedicineResource($medicine->load('batches')),
            'Medicine updated successfully'
        );
    }

    /**
     * Remove the specified medicine.
     */
    public function destroy(Medicine $medicine): JsonResponse
    {
        $medicine->delete();

        return $this->successResponse(null, 'Medicine deleted successfully');
    }

    /**
     * Get low stock medicines.
     */
    public function lowStock(Request $request): JsonResponse
    {
        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $medicines = Medicine::where('clinic_id', $clinicId)
            ->where('is_active', true)
            ->whereRaw('stock_quantity <= reorder_level')
            ->with(['batches' => function ($query) {
                $query->where('expiry_date', '>', now())
                    ->where('current_quantity', '>', 0);
            }])
            ->orderBy('stock_quantity')
            ->paginate($request->get('per_page', 15));

        return $this->successResponse(
            MedicineResource::collection($medicines)->response()->getData(true),
            'Low stock medicines retrieved successfully'
        );
    }
}
