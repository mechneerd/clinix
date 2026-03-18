<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'clinic_id', 'room_number', 'type', 'floor', 
        'daily_rate', 'is_occupied', 'is_active'
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function ward() { return $this->belongsTo(Ward::class); }
    public function beds() { return $this->hasMany(Bed::class); }
    public function admissions() { return $this->hasMany(PatientAdmission::class); }
    public function currentAdmission() { return $this->hasOne(PatientAdmission::class)->where('status', 'admitted'); }
}
