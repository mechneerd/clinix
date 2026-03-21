<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/invoices",
     *     summary="Get all invoices",
     *     tags={"Invoices"},
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
     *         @OA\Schema(type="string", enum={"pending","paid","partially_paid","cancelled"})
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoices retrieved successfully"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $query = Invoice::with(['patient', 'payments']);

        // Filter based on user type
        if ($user->isPatient()) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->isStaff()) {
            $clinicId = $user->staff->clinic_id;
            $query->where('clinic_id', $clinicId);
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

        $invoices = $query->latest()->paginate($request->get('per_page', 15));

        return $this->paginatedResponse($invoices, 'Invoices retrieved successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/invoices/{id}",
     *     summary="Get a specific invoice",
     *     tags={"Invoices"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found"
     *     )
     * )
     */
    public function show(Invoice $invoice): JsonResponse
    {
        $invoice->load(['patient', 'payments']);

        return $this->successResponse($invoice, 'Invoice retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/invoices/{id}/pay",
     *     summary="Record a payment for an invoice",
     *     tags={"Invoices"},
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
     *             required={"amount","payment_method"},
     *             @OA\Property(property="amount", type="number", format="float", example=100.00),
     *             @OA\Property(property="payment_method", type="string", enum={"cash","card","bank_transfer","mobile_money"}),
     *             @OA\Property(property="reference", type="string"),
     *             @OA\Property(property="notes", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment recorded successfully"
     *     )
     * )
     */
    public function pay(Request $request, Invoice $invoice): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,mobile_money',
            'reference' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = auth()->user();
        
        // Create payment record
        $payment = $invoice->payments()->create([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'paid_by' => $user->id,
        ]);

        // Update invoice paid amount
        $invoice->increment('paid_amount', $request->amount);

        // Update invoice status
        if ($invoice->paid_amount >= $invoice->total_amount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($invoice->paid_amount > 0) {
            $invoice->update(['status' => 'partially_paid']);
        }

        $invoice->load(['patient', 'payments']);

        return $this->successResponse($invoice, 'Payment recorded successfully');
    }
}
