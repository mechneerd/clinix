<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = ['clinic_id', 'patient_id', 'rating', 'comment', 'type'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
}
