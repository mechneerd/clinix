<?php

namespace App\Events;

use App\Models\LabOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LabReportReady implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public LabOrder $labOrder) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('patient.' . $this->labOrder->patient_id),
            new PrivateChannel('clinic.' . $this->labOrder->clinic_id),
        ];
    }

    public function broadcastAs(): string { return 'lab.report.ready'; }

    public function broadcastWith(): array
    {
        return [
            'order_id'     => $this->labOrder->id,
            'order_number' => $this->labOrder->order_number,
            'lab_name'     => $this->labOrder->lab->name ?? '',
            'completed_at' => $this->labOrder->completed_at?->format('M d, Y H:i'),
            'items_count'  => $this->labOrder->items->count(),
        ];
    }
}