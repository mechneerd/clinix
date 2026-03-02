<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id','clinic_id','day_of_week',
        'start_time','end_time','slot_duration',
        'buffer_time','max_patients','is_available',
    ];
    protected $casts = ['is_available' => 'boolean'];

    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
    public function clinic() { return $this->belongsTo(Clinic::class); }
}
