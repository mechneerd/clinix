<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentBooked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Appointment $appointment) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('patient.' . $this->appointment->patient_id),
            new PrivateChannel('clinic.' . $this->appointment->clinic_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'appointment.booked';
    }

    public function broadcastWith(): array
    {
        return [
            'id'               => $this->appointment->id,
            'appointment_number'=> $this->appointment->appointment_number,
            'date'             => $this->appointment->appointment_date->format('M d, Y'),
            'time'             => $this->appointment->start_time,
            'status'           => $this->appointment->status,
            'doctor_name'      => $this->appointment->doctor->name ?? '',
            'clinic_name'      => $this->appointment->clinic->name ?? '',
        ];
    }
}