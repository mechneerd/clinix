<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DischargeSummary extends Model
{
    protected $fillable = [
        'patient_admission_id', 'final_diagnosis', 'clinical_summary', 
        'treatment_given', 'discharge_condition', 
        'follow_up_instructions', 'discharged_at', 'discharged_by'
    ];

    protected $casts = [
        'discharged_at' => 'datetime',
    ];

    public function admission() { return $this->belongsTo(PatientAdmission::class, 'patient_admission_id'); }
    public function finisher() { return $this->belongsTo(User::class, 'discharged_by'); }
}
