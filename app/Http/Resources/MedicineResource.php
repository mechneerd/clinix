<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
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
            'clinic_id' => $this->clinic_id,
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'category' => $this->category,
            'dosage_form' => $this->dosage_form,
            'strength' => $this->strength,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'reorder_level' => $this->reorder_level,
            'is_active' => $this->is_active,
            'clinic' => new ClinicResource($this->whenLoaded('clinic')),
            'batches' => MedicineBatchResource::collection($this->whenLoaded('batches')),
            'medicine_category' => new MedicineCategoryResource($this->whenLoaded('medicineCategory')),
            'brand' => new MedicineBrandResource($this->whenLoaded('brand')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
