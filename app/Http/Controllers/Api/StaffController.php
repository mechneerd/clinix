<?php

namespace App\Http\Controllers\Api;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/staff",
     *     summary="Get all staff members",
     *     tags={"Staff"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filter by role",
     *         @OA\Schema(type="string", enum={"doctor","nurse","receptionist","lab_worker","pharmacy_worker","lab_manager","pharmacy_manager","reception_manager"})
     *     ),
     *     @OA\Parameter(
     *         name="department_id",
     *         in="query",
     *         description="Filter by department ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or employee ID",
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
     *         description="Staff members retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Staff::with(['user', 'clinic', 'department'])
            ->where('is_active', true);

        // Filter by clinic based on user type
        if ($user->isStaff()) {
            $query->where('clinic_id', $user->staff->clinic_id);
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        // Apply filters
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $staff = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($staff, 'Staff members retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/staff",
     *     summary="Create a new staff member",
     *     tags={"Staff"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","phone","password","department_id","employee_id","role"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="+1234567890"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="department_id", type="integer", example=1),
     *             @OA\Property(property="employee_id", type="string", example="EMP001"),
     *             @OA\Property(property="role", type="string", enum={"nurse","receptionist","lab_worker","pharmacy_worker","lab_manager","pharmacy_manager","reception_manager"}),
     *             @OA\Property(property="qualification", type="string", example="MBBS"),
     *             @OA\Property(property="license_number", type="string"),
     *             @OA\Property(property="consultation_fee", type="number", format="float", example=50.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Staff member created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|string|unique:staff,employee_id',
            'role' => 'required|string|in:nurse,receptionist,lab_worker,pharmacy_worker,lab_manager,pharmacy_manager,reception_manager',
            'qualification' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        DB::transaction(function () use ($request, $clinicId) {
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'user_type' => 'staff',
                'is_active' => true,
            ]);

            $newUser->assignRole($request->role);

            Staff::create([
                'user_id' => $newUser->id,
                'clinic_id' => $clinicId,
                'department_id' => $request->department_id,
                'employee_id' => $request->employee_id,
                'role' => $request->role,
                'qualification' => $request->qualification,
                'license_number' => $request->license_number,
                'consultation_fee' => $request->consultation_fee,
                'joining_date' => now(),
                'is_active' => true,
            ]);
        });

        $staff = Staff::where('employee_id', $request->employee_id)
            ->with(['user', 'clinic', 'department'])
            ->first();

        return $this->successResponse($staff, 'Staff member created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/staff/{id}",
     *     summary="Get a specific staff member",
     *     tags={"Staff"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Staff member retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Staff member not found"
     *     )
     * )
     */
    public function show(Staff $staff): JsonResponse
    {
        $staff->load(['user', 'clinic', 'department', 'schedules']);

        return $this->successResponse($staff, 'Staff member retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/staff/{id}",
     *     summary="Update a staff member",
     *     tags={"Staff"},
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
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="department_id", type="integer"),
     *             @OA\Property(property="employee_id", type="string"),
     *             @OA\Property(property="role", type="string"),
     *             @OA\Property(property="qualification", type="string"),
     *             @OA\Property(property="license_number", type="string"),
     *             @OA\Property(property="consultation_fee", type="number"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Staff member updated successfully"
     *     )
     * )
     */
    public function update(Request $request, Staff $staff): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $staff->user_id,
            'phone' => 'sometimes|string|max:20',
            'department_id' => 'sometimes|exists:departments,id',
            'employee_id' => 'sometimes|string|unique:staff,employee_id,' . $staff->id,
            'role' => 'sometimes|string|in:doctor,nurse,receptionist,lab_worker,pharmacy_worker,lab_manager,pharmacy_manager,reception_manager',
            'qualification' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        DB::transaction(function () use ($request, $staff) {
            // Update user if name, email, or phone changed
            if ($request->hasAny(['name', 'email', 'phone'])) {
                $staff->user->update($request->only(['name', 'email', 'phone']));
            }

            // Update role if changed
            if ($request->has('role') && $staff->role !== $request->role) {
                $staff->user->syncRoles([$request->role]);
            }

            $staff->update($request->except(['name', 'email', 'phone']));
        });

        $staff->load(['user', 'clinic', 'department']);

        return $this->successResponse($staff, 'Staff member updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/staff/{id}",
     *     summary="Delete a staff member",
     *     tags={"Staff"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Staff member deleted successfully"
     *     )
     * )
     */
    public function destroy(Staff $staff): JsonResponse
    {
        DB::transaction(function () use ($staff) {
            $user = $staff->user;
            $staff->delete();
            $user->update(['is_active' => false]);
        });

        return $this->successResponse(null, 'Staff member deleted successfully');
    }
}
