<?php

namespace App\Services;

use App\Events\LabReportReady;
use App\Models\LabOrder;
use App\Models\User;
use App\Notifications\LabReportReadyNotification;

class LabService
{
    public function getPatientOrders(int $patientId, ?string $status = null)
    {
        return LabOrder::with(['lab', 'doctor', 'items.labTest', 'report'])
            ->where('patient_id', $patientId)
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function getOrderDetail(int $orderId, int $patientId): ?LabOrder
    {
        return LabOrder::with(['lab', 'doctor', 'clinic', 'items.labTest', 'report'])
            ->where('id', $orderId)
            ->where('patient_id', $patientId)
            ->first();
    }

    public function markReportReady(LabOrder $order): LabOrder
    {
        $order->update([
            'status'       => 'completed',
            'completed_at' => now(),
        ]);

        $order->load(['lab', 'items', 'patient']);

        // Notify patient
        $order->patient->notify(new LabReportReadyNotification($order));

        // Broadcast via Reverb
        broadcast(new LabReportReady($order));

        return $order;
    }
}
