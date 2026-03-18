<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'patient_id', 'referring_doctor_id', 'referred_to_doctor', 
        'referred_to_clinic', 'reason', 'status'
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function referringDoctor() { return $this->belongsTo(Staff::class, 'referring_doctor_id'); }
}
