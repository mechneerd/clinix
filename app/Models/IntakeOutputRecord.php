<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntakeOutputRecord extends Model
{
    protected $fillable = ['patient_admission_id', 'type', 'route', 'volume_ml', 'recorded_at', 'recorded_by'];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function admission() { return $this->belongsTo(PatientAdmission::class, 'patient_admission_id'); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
}
