<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'user_type', // super_admin, clinic_admin, staff, patient
        'is_active',
        'last_login_at',
        'email_verified_at',
        'google_id',
        'google_token',
        'google_refresh_token',
        'otp',
        'otp_expires_at',
        'country_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'country_id' => 'integer',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function clinic()
    {
        return $this->hasOne(Clinic::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function isClinicAdmin(): bool
    {
        return $this->hasRole('clinic-admin');
    }

    public function isStaff(): bool
    {
        return $this->hasAnyRole(['doctor', 'nurse', 'lab_manager', 'pharmacy_manager', 'reception_manager', 'lab_worker', 'pharmacy_worker', 'receptionist']);
    }

    public function isPatient(): bool
    {
        return $this->hasRole('patient');
    }

    public function getDashboardRoute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'super-admin.dashboard';
        }

        if ($this->isClinicAdmin()) {
            return 'clinic.dashboard';
        }

        if ($this->isStaff()) {
            $role = $this->roles->first()->name ?? '';

            // Map roles to their dashboard routes
            return match ($role) {
                    'doctor' => 'doctor.dashboard',
                    'nurse' => 'nurse.dashboard',
                    'lab_manager', 'lab_worker' => 'lab.dashboard',
                    'pharmacy_manager', 'pharmacy_worker' => 'pharmacy.dashboard',
                    'reception_manager', 'receptionist' => 'reception.dashboard',
                    default => 'clinic.dashboard', // Fallback
                };
        }

        if ($this->isPatient()) {
            return 'patient.dashboard';
        }

        return 'home';
    }

    public function generateOtp()
    {
        $this->otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->otp;
    }

    public function isOtpValid($otp)
    {
        return (string) $this->otp === (string) $otp && $this->otp_expires_at && $this->otp_expires_at->isFuture();
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class)->withPivot('is_active')->withTimestamps();
    }

    public function isModuleEnabled(string $slug): bool
    {
        // 1. Get the module
        $module = Module::where('slug', $slug)->first();

        if (!$module) {
            return false;
        }

        // 2. If module is not active globally, it's disabled for everyone
        if (!$module->is_active) {
            return false;
        }

        // 3. If it's a core module, it's always enabled
        if ($module->is_core) {
            return true;
        }

        // 4. Check user-specific override (only relevant for clinic-admins/admins)
        $userModule = $this->modules()->where('module_id', $module->id)->first();
        
        if ($userModule) {
            return (bool) $userModule->pivot->is_active;
        }

        // 5. Default to enabled if no override exists
        return true;
    }

    public function clearOtp()
    {
        $this->otp = null;
        $this->otp_expires_at = null;
        $this->save();
    }

    public function auditLogs() { return $this->hasMany(SystemAuditLog::class); }
    public function requisitions() { return $this->hasMany(Requisition::class, 'requested_by'); }
    public function announcementsCreated() { return $this->hasMany(Announcement::class, 'created_by'); }
    public function incidentsReported() { return $this->hasMany(Incident::class, 'reported_by'); }
}