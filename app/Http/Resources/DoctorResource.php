<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'user_id' => $this->user_id,
            'clinic_id' => $this->clinic_id,
            'department_id' => $this->department_id,
            'employee_id' => $this->employee_id,
            'role' => $this->role,
            'role_display' => $this->role_display,
            'qualification' => $this->qualification,
            'license_number' => $this->license_number,
            'joining_date' => $this->joining_date?->toDateString(),
            'consultation_fee' => $this->consultation_fee,
            'working_hours' => $this->working_hours,
            'is_active' => $this->is_active,
            'full_name' => $this->full_name,
            'user' => new UserResource($this->whenLoaded('user')),
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'schedules' => DoctorScheduleResource::collection($this->whenLoaded('schedules')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
