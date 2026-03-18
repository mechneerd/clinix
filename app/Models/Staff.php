<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'user_id', 'clinic_id', 'department_id', 'employee_id', 'role',
        'qualification', 'license_number', 'joining_date', 'consultation_fee',
        'working_hours', 'is_active'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'consultation_fee' => 'decimal:2',
        'working_hours' => 'array',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->role === 'doctor' ? $this->hasMany(Appointment::class, 'doctor_id') : $this->hasMany(Appointment::class, 'staff_id');
    }

    public function schedules() { return $this->hasMany(DoctorSchedule::class, 'staff_id'); }
    public function timeSlots() { return $this->hasMany(TimeSlot::class, 'staff_id'); }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'doctor_id');
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class, 'doctor_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'doctor' => 'Doctor',
            'nurse' => 'Nurse',
            'lab_manager' => 'Lab Manager',
            'pharmacy_manager' => 'Pharmacy Manager',
            'reception_manager' => 'Reception Manager',
            'lab_worker' => 'Lab Technician',
            'pharmacy_worker' => 'Pharmacy Assistant',
            'receptionist' => 'Receptionist',
            default => ucfirst(str_replace('_', ' ', $this->role)),
        };
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['lab_manager', 'pharmacy_manager', 'reception_manager']);
    }

    public function jobPosition() { return $this->belongsTo(JobPosition::class); }
    public function leaves() { return $this->hasMany(EmployeeLeave::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }
    public function payrolls() { return $this->hasMany(Payroll::class); }
    public function performanceReviews() { return $this->hasMany(PerformanceReview::class); }
}