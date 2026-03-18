<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAdmission extends Model
{
    protected $fillable = [
        'patient_id', 'room_id', 'bed_id', 'admitted_at', 'discharged_at', 
        'reason', 'status', 'admitted_by'
    ];

    protected $casts = [
        'admitted_at' => 'datetime',
        'discharged_at' => 'datetime'
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function bed() { return $this->belongsTo(Bed::class); }
    public function admittedBy() { return $this->belongsTo(User::class, 'admitted_by'); }
}
