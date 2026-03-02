<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    protected $fillable = [
        'user_id','clinic_id','department_id','employee_id',
        'qualification','specializations','experience_years',
        'registration_number','license_number','license_expiry',
        'biography','education','awards','consultation_fee',
        'follow_up_days','is_available_for_online','joining_date',
        'leaving_date','employment_type','salary',
    ];
    protected $casts = [
        'education'                => 'array',
        'awards'                   => 'array',
        'is_available_for_online'  => 'boolean',
        'joining_date'             => 'date',
        'leaving_date'             => 'date',
        'license_expiry'           => 'date',
        'consultation_fee'         => 'decimal:2',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function clinic()     { return $this->belongsTo(Clinic::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function schedules()  { return $this->hasMany(DoctorSchedule::class, 'doctor_id', 'user_id'); }
}
