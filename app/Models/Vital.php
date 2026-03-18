<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vital extends Model
{
    protected $fillable = [
        'patient_id', 'appointment_id', 'blood_pressure', 'temperature', 
        'pulse', 'weight', 'height', 'bmi', 'respiratory_rate', 
        'oxygen_saturation', 'recorded_by'
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
}
