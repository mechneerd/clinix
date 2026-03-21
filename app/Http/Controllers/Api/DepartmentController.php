<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/departments",
     *     summary="Get all departments",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name",
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
     *         description="Departments retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Department::with(['clinic'])
            ->where('is_active', true);

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
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $departments = $query->withCount('staff')
            ->latest()
            ->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($departments, 'Departments retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/departments",
     *     summary="Create a new department",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Cardiology"),
     *             @OA\Property(property="code", type="string", example="CARD"),
     *             @OA\Property(property="description", type="string", example="Heart and cardiovascular system")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Department created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $department = Department::create(array_merge(
            $validator->validated(),
            ['clinic_id' => $clinicId]
        ));

        $department->load('clinic');

        return $this->successResponse($department, 'Department created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/departments/{id}",
     *     summary="Get a specific department",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Department retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Department not found"
     *     )
     * )
     */
    public function show(Department $department): JsonResponse
    {
        $department->load(['clinic', 'staff.user']);

        return $this->successResponse($department, 'Department retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/departments/{id}",
     *     summary="Update a department",
     *     tags={"Departments"},
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
     *             @OA\Property(property="code", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Department updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Department $department): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50|unique:departments,code,' . $department->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $department->update($validator->validated());
        $department->load('clinic');

        return $this->successResponse($department, 'Department updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/departments/{id}",
     *     summary="Delete a department",
     *     tags={"Departments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Department deleted successfully"
     *     )
     * )
     */
    public function destroy(Department $department): JsonResponse
    {
        // Check if department has staff
        if ($department->staff()->count() > 0) {
            return $this->errorResponse('Cannot delete department with active staff members', 400);
        }

        $department->delete();

        return $this->successResponse(null, 'Department deleted successfully');
    }
}
