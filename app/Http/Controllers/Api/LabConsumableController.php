<?php

namespace App\Http\Controllers\Api;

use App\Models\LabConsumable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LabConsumableController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/lab-consumables",
     *     summary="Get all lab consumables",
     *     tags={"Lab Consumables"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or description",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="Filter by active status",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab consumables retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = LabConsumable::with(['clinic']);

        // Filter by clinic based on user type
        if ($user->isStaff()) {
            $query->where('clinic_id', $user->staff->clinic_id);
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $consumables = $query->orderBy('name')
            ->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($consumables, 'Lab consumables retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/lab-consumables",
     *     summary="Create a new lab consumable",
     *     tags={"Lab Consumables"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","unit","price","stock_quantity","reorder_level"},
     *             @OA\Property(property="name", type="string", example="Test Tubes"),
     *             @OA\Property(property="description", type="string", example="Glass test tubes for lab use"),
     *             @OA\Property(property="unit", type="string", example="box"),
     *             @OA\Property(property="price", type="number", format="float", example=25.00),
     *             @OA\Property(property="stock_quantity", type="integer", example=100),
     *             @OA\Property(property="reorder_level", type="integer", example=20)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lab consumable created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $consumable = LabConsumable::create(array_merge(
            $validator->validated(),
            ['clinic_id' => $clinicId]
        ));

        $consumable->load('clinic');

        return $this->successResponse($consumable, 'Lab consumable created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/lab-consumables/{id}",
     *     summary="Get a specific lab consumable",
     *     tags={"Lab Consumables"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab consumable retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lab consumable not found"
     *     )
     * )
     */
    public function show(LabConsumable $labConsumable): JsonResponse
    {
        $labConsumable->load('clinic');

        return $this->successResponse($labConsumable, 'Lab consumable retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/lab-consumables/{id}",
     *     summary="Update a lab consumable",
     *     tags={"Lab Consumables"},
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
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="unit", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock_quantity", type="integer"),
     *             @OA\Property(property="reorder_level", type="integer"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab consumable updated successfully"
     *     )
     * )
     */
    public function update(Request $request, LabConsumable $labConsumable): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'sometimes|required|string|max:50',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'reorder_level' => 'sometimes|required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $labConsumable->update($validator->validated());
        $labConsumable->load('clinic');

        return $this->successResponse($labConsumable, 'Lab consumable updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/lab-consumables/{id}",
     *     summary="Delete a lab consumable",
     *     tags={"Lab Consumables"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab consumable deleted successfully"
     *     )
     * )
     */
    public function destroy(LabConsumable $labConsumable): JsonResponse
    {
        $labConsumable->delete();

        return $this->successResponse(null, 'Lab consumable deleted successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/lab-consumables/low-stock",
     *     summary="Get low stock lab consumables",
     *     tags={"Lab Consumables"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Low stock consumables retrieved successfully"
     *     )
     * )
     */
    public function lowStock(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = LabConsumable::with(['clinic'])
            ->where('is_active', true)
            ->whereRaw('stock_quantity <= reorder_level');

        // Filter by clinic based on user type
        if ($user->isStaff()) {
            $query->where('clinic_id', $user->staff->clinic_id);
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        $consumables = $query->orderBy('stock_quantity')
            ->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($consumables, 'Low stock consumables retrieved successfully');
    }
}
