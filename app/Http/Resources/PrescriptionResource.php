<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
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
            'medical_record_id' => $this->medical_record_id,
            'prescription_no' => $this->prescription_no,
            'prescribed_date' => $this->prescribed_date?->toDateString(),
            'notes' => $this->notes,
            'is_dispensed' => $this->is_dispensed,
            'dispensed_at' => $this->dispensed_at?->toDateTimeString(),
            'medical_record' => new MedicalRecordResource($this->whenLoaded('medicalRecord')),
            'items' => PrescriptionItemResource::collection($this->whenLoaded('items')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'updater' => new UserResource($this->whenLoaded('updater')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
