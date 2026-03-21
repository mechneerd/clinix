<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_date' => $this->appointment_date?->toDateString(),
            'start_time' => $this->start_time?->format('H:i'),
            'end_time' => $this->end_time?->format('H:i'),
            'type' => $this->type,
            'status' => $this->status,
            'chief_complaint' => $this->chief_complaint,
            'notes' => $this->notes,
            'fee' => $this->fee,
            'checked_in_at' => $this->checked_in_at?->toDateTimeString(),
            'started_at' => $this->started_at?->toDateTimeString(),
            'completed_at' => $this->completed_at?->toDateTimeString(),
            'reminder_minutes' => $this->reminder_minutes,
            'reminder_at' => $this->reminder_at?->toDateTimeString(),
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'doctor' => new StaffResource($this->whenLoaded('doctor')),
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'medical_record' => new MedicalRecordResource($this->whenLoaded('medicalRecord')),
            'vitals' => new VitalResource($this->whenLoaded('vitals')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
