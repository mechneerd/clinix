<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'package_id', 'name', 'slug', 'description', 'email', 'phone',
        'address', 'city', 'state', 'country', 'logo', 'theme_settings',
        'package_expires_at', 'status'
    ];

    protected $casts = [
        'theme_settings' => 'array',
        'package_expires_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function specialties()
    {
        return $this->hasMany(Specialty::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'clinic_package')
                    ->withPivot('expires_at', 'is_active')
                    ->withTimestamps();
    }

    public function rooms() { return $this->hasMany(Room::class); }
    public function expenses() { return $this->hasMany(ClinicExpense::class); }
    public function settings() { return $this->hasMany(ClinicSetting::class); }

    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'clinic_patient')
                    ->withPivot('registered_at', 'registration_type')
                    ->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function isModuleEnabled(string $slug): bool
    {
        // Delegate to the clinic admin (owner)
        if ($this->admin) {
            return $this->admin->isModuleEnabled($slug);
        }

        // Fallback or default global check
        $module = Module::where('slug', $slug)->first();
        return $module ? (bool) $module->is_active : false;
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ($this->package_expires_at === null || $this->package_expires_at->isFuture());
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-clinic.png');
    }

    public function wards() { return $this->hasMany(Ward::class); }
    public function suppliers() { return $this->hasMany(Supplier::class); }
    public function warehouses() { return $this->hasMany(Warehouse::class); }
    public function announcements() { return $this->hasMany(Announcement::class); }
    public function ledgerAccounts() { return $this->hasMany(LedgerAccount::class); }
    public function taxes() { return $this->hasMany(Tax::class); }
    public function discounts() { return $this->hasMany(Discount::class); }
}