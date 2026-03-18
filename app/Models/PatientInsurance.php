<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientInsurance extends Model
{
    protected $table = 'patient_insurance';
    protected $fillable = [
        'patient_id', 'provider_name', 'policy_number', 'group_number', 
        'coverage_details', 'expiry_date', 'is_active'
    ];

    protected $casts = ['expiry_date' => 'date'];

    public function patient() { return $this->belongsTo(Patient::class); }
}
