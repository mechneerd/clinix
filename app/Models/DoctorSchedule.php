<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'staff_id', 'day_of_week', 'start_time', 'end_time', 
        'break_start', 'break_end', 'is_active'
    ];

    public function doctor() { return $this->belongsTo(Staff::class, 'staff_id'); }
}
