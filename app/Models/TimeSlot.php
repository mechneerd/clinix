<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'staff_id', 'start_time', 'end_time', 'status', 'appointment_id'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function doctor() { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function appointment() { return $this->belongsTo(Appointment::class); }
}
