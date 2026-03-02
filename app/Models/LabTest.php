<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    protected $fillable = [
        'lab_id','category_id','name','code','description',
        'preparation_instructions','price','cost_price',
        'sample_type','sample_volume','default_turnaround_time',
        'result_type','unit','reference_range','normal_values',
        'abnormal_interpretation','test_method','equipment_required','is_active',
    ];
    protected $casts = ['reference_range' => 'array', 'is_active' => 'boolean', 'price' => 'decimal:2'];

    public function lab()        { return $this->belongsTo(Lab::class); }
    public function orderItems() { return $this->hasMany(LabOrderItem::class); }
}
