<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientVisit extends Model
{
    protected $fillable = [
        'appointment_id','clinic_id','patient_id','doctor_id',
        'height','weight','bmi','blood_pressure_systolic','blood_pressure_diastolic',
        'pulse_rate','temperature','respiratory_rate','oxygen_saturation',
        'chief_complaints','presenting_history','past_history','family_history',
        'personal_history','allergies','examination_findings','diagnosis',
        'differential_diagnosis','icd_codes','status','started_at','completed_at',
    ];
    protected $casts = [
        'icd_codes'    => 'array',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function appointment()  { return $this->belongsTo(Appointment::class); }
    public function patient()      { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor()       { return $this->belongsTo(User::class, 'doctor_id'); }
    public function prescriptions(){ return $this->hasMany(Prescription::class, 'visit_id'); }
    public function labOrders()    { return $this->hasMany(LabOrder::class, 'visit_id'); }

    public function getBloodPressureAttribute(): string
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return "{$this->blood_pressure_systolic}/{$this->blood_pressure_diastolic} mmHg";
        }
        return '—';
    }
}
