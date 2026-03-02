<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Appointment $appointment,
        public string $oldStatus
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('patient.' . $this->appointment->patient_id),
            new PrivateChannel('clinic.' . $this->appointment->clinic_id),
        ];
    }

    public function broadcastAs(): string { return 'appointment.status.updated'; }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->appointment->id,
            'number'     => $this->appointment->appointment_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->appointment->status,
            'date'       => $this->appointment->appointment_date->format('M d, Y'),
            'doctor'     => $this->appointment->doctor->name ?? '',
        ];
    }
}