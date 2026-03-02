<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'registration_type',
        'current_clinic_id',
        'avatar',
        'email_verified',
        'phone_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'phone_verified_at'  => 'datetime',
            'trial_ends_at'      => 'datetime',
            'email_verified'     => 'boolean',
            'phone_verified'     => 'boolean',
        ];  
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class, 'owner_id');
    }

    public function currentClinic()
    {
        return $this->belongsTo(Clinic::class, 'current_clinic_id');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->latest();
    }

    // Patient relationships
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function upcomingAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('start_time');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class, 'patient_id');
    }

    public function visits()
    {
        return $this->hasMany(PatientVisit::class, 'patient_id');
    }

    // Doctor relationships
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->registration_type === 'admin';
    }

    public function isPatient(): bool
    {
        return $this->registration_type === 'patient';
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }
}
