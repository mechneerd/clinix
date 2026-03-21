<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'billing_cycle' => $this->billing_cycle,
            'duration_days' => $this->duration_days,
            'is_active' => $this->is_active,
            'is_approved' => $this->is_approved,
            'max_clinics' => $this->max_clinics,
            'max_labs' => $this->max_labs,
            'max_doctors' => $this->max_doctors,
            'max_staff' => $this->max_staff,
            'max_patients_per_month' => $this->max_patients_per_month,
            'storage_limit_mb' => $this->storage_limit_mb,
            'api_access' => $this->api_access,
            'white_label' => $this->white_label,
            'advanced_reporting' => $this->advanced_reporting,
            'sms_notifications' => $this->sms_notifications,
            'telemedicine' => $this->telemedicine,
            'clinics_count' => $this->whenCounted('clinics'),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
