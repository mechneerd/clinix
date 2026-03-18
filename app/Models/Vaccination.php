<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    protected $fillable = [
        'patient_id', 'vaccine_name', 'batch_number', 'administered_at', 
        'next_dose_at', 'administered_by', 'notes'
    ];

    protected $casts = ['administered_at' => 'date', 'next_dose_at' => 'date'];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function administrator() { return $this->belongsTo(User::class, 'administered_by'); }
}
