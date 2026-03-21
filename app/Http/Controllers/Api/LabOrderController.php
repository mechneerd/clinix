<?php

namespace App\Http\Controllers\Api;

use App\Models\LabOrder;
use App\Models\LabOrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LabOrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/lab-orders",
     *     summary="Get all lab orders",
     *     tags={"Lab Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="patient_id",
     *         in="query",
     *         description="Filter by patient ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         @OA\Schema(type="string", enum={"pending","in_progress","completed","cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab orders retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = LabOrder::with(['patient', 'doctor.user', 'clinic', 'items.labTest']);

        // Filter based on user type
        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $clinicId = $user->staff->clinic_id;
            $query->where('clinic_id', $clinicId);
            
            // Doctors can only see their own orders
            if ($user->staff->isDoctor()) {
                $query->where('doctor_id', $user->staff->id);
            }
        } elseif ($user->isClinicAdmin()) {
            $query->where('clinic_id', $user->clinic->id);
        }

        // Apply filters
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($orders, 'Lab orders retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/lab-orders",
     *     summary="Create a new lab order",
     *     tags={"Lab Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patient_id","doctor_id","items"},
     *             @OA\Property(property="patient_id", type="integer", example=1),
     *             @OA\Property(property="doctor_id", type="integer", example=1),
     *             @OA\Property(property="appointment_id", type="integer", example=1),
     *             @OA\Property(property="items", type="array", @OA\Items(
     *                 type="object",
     *                 required={"lab_test_id"},
     *                 @OA\Property(property="lab_test_id", type="integer", example=1),
     *                 @OA\Property(property="notes", type="string")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lab order created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:staff,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'items' => 'required|array|min:1',
            'items.*.lab_test_id' => 'required|exists:lab_tests,id',
            'items.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        $clinicId = $user->isStaff() ? $user->staff->clinic_id : $user->clinic->id;

        $order = DB::transaction(function () use ($request, $clinicId, $user) {
            $order = LabOrder::create([
                'clinic_id' => $clinicId,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_id' => $request->appointment_id,
                'order_no' => 'LAB-' . strtoupper(uniqid()),
                'status' => 'pending',
                'created_by' => $user->id,
            ]);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $labTest = \App\Models\LabTest::find($item['lab_test_id']);
                $order->items()->create([
                    'lab_test_id' => $item['lab_test_id'],
                    'price' => $labTest->price,
                    'notes' => $item['notes'] ?? null,
                ]);
                $totalAmount += $labTest->price;
            }

            $order->update(['total_amount' => $totalAmount]);

            return $order;
        });

        $order->load(['patient', 'doctor.user', 'clinic', 'items.labTest']);

        return $this->successResponse($order, 'Lab order created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/lab-orders/{id}",
     *     summary="Get a specific lab order",
     *     tags={"Lab Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab order retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lab order not found"
     *     )
     * )
     */
    public function show(LabOrder $labOrder): JsonResponse
    {
        $labOrder->load(['patient', 'doctor.user', 'clinic', 'items.labTest']);

        return $this->successResponse($labOrder, 'Lab order retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/lab-orders/{id}",
     *     summary="Update a lab order",
     *     tags={"Lab Orders"},
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
     *             @OA\Property(property="status", type="string", enum={"pending","in_progress","completed","cancelled"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab order updated successfully"
     *     )
     * )
     */
    public function update(Request $request, LabOrder $labOrder): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|required|in:pending,in_progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        $labOrder->update(array_merge(
            $validator->validated(),
            ['updated_by' => $user->id]
        ));

        $labOrder->load(['patient', 'doctor.user', 'clinic', 'items.labTest']);

        return $this->successResponse($labOrder, 'Lab order updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/lab-orders/{id}",
     *     summary="Delete a lab order",
     *     tags={"Lab Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lab order deleted successfully"
     *     )
     * )
     */
    public function destroy(LabOrder $labOrder): JsonResponse
    {
        $labOrder->delete();

        return $this->successResponse(null, 'Lab order deleted successfully');
    }
}
