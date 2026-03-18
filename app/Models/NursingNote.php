<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingNote extends Model
{
    protected $fillable = ['patient_admission_id', 'nurse_id', 'observation', 'action_taken', 'recorded_at'];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function admission() { return $this->belongsTo(PatientAdmission::class, 'patient_admission_id'); }
    public function nurse() { return $this->belongsTo(Staff::class, 'nurse_id'); }
}
