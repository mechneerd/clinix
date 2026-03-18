<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'patient_code', 'first_name', 'last_name', 'email', 'phone', 'country_id',
        'date_of_birth', 'gender', 'blood_group', 'address', 'emergency_contact_name',
        'emergency_contact_phone', 'allergies', 'medical_history', 'is_active'
    ];

    public function country() { return $this->belongsTo(Country::class); }

    protected $casts = [
        'date_of_birth' => 'date',
        'allergies' => 'array',
        'medical_history' => 'array',
        'is_active' => 'boolean',
    ];

    public function allergies() { return $this->hasMany(PatientAllergy::class); }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($patient) {
            $patient->patient_code = 'PAT-' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_patient')
                    ->withPivot('registered_at', 'registration_type')
                    ->withTimestamps();
    }

    public function vitals() { return $this->hasMany(Vital::class); }
    public function vaccinations() { return $this->hasMany(Vaccination::class); }
    public function insurance() { return $this->hasMany(PatientInsurance::class); }
    public function documents() { return $this->hasMany(PatientDocument::class); }
    public function admissions() { return $this->hasMany(PatientAdmission::class); }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute(): int
    {
        return $this->date_of_birth->age;
    }
}