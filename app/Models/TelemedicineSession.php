<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelemedicineSession extends Model
{
    protected $fillable = [
        'appointment_id', 'platform', 'session_url', 'duration_minutes', 'recording_path'
    ];

    public function appointment() { return $this->belongsTo(Appointment::class); }
}
