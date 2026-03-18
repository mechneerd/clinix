<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    protected $fillable = [
        'clinic_id', 'patient_id', 'doctor_id', 'appointment_date', 'start_time',
        'end_time', 'type', 'status', 'chief_complaint', 'notes', 'fee',
        'checked_in_at', 'started_at', 'completed_at', 'reminder_minutes', 'reminder_at'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'fee' => 'decimal:2',
        'checked_in_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'reminder_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($appointment) {
            if ($appointment->isDirty('reminder_minutes') || $appointment->isDirty('start_time') || $appointment->isDirty('appointment_date')) {
                if ($appointment->reminder_minutes && $appointment->start_time && $appointment->appointment_date) {
                    // Combine date and time
                    $startDateTime = \Carbon\Carbon::parse($appointment->appointment_date->format('Y-m-d') . ' ' . \Carbon\Carbon::parse($appointment->start_time)->format('H:i:s'));
                    $appointment->reminder_at = $startDateTime->subMinutes($appointment->reminder_minutes);
                } else {
                    $appointment->reminder_at = null;
                }
            }
        });
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function vitals() { return $this->hasOne(Vital::class); }
    public function telemedicineSession() { return $this->hasOne(TelemedicineSession::class); }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Staff::class, 'doctor_id');
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function prescription()
    {
        return $this->hasOneThrough(Prescription::class, MedicalRecord::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
                     ->whereIn('status', ['scheduled', 'confirmed']);
    }

    public function canCheckIn(): bool
    {
        return $this->status === 'scheduled' && $this->appointment_date->isToday();
    }

    public function canComplete(): bool
    {
        return in_array($this->status, ['scheduled', 'confirmed']);
    }
}