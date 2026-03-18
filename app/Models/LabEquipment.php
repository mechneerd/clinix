<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabEquipment extends Model
{
    protected $fillable = [
        'clinic_id', 'name', 'model_number', 
        'serial_number', 'purchase_date', 
        'last_calibration_date', 'next_calibration_due', 'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'last_calibration_date' => 'date',
        'next_calibration_due' => 'date',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
