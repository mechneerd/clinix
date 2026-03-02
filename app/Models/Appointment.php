<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'appointment_number','clinic_id','doctor_id','patient_id','department_id',
        'booked_by','appointment_date','start_time','end_time','duration',
        'type','status','payment_status','symptoms','notes',
        'cancellation_reason','cancelled_by','cancelled_at',
        'previous_appointment_id','reminder_sent_at','sms_reminder_sent_at',
        'meeting_link','meeting_id',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'cancelled_at'     => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    public function patient()  { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor()   { return $this->belongsTo(User::class, 'doctor_id'); }
    public function clinic()   { return $this->belongsTo(Clinic::class); }
    public function department(){ return $this->belongsTo(Department::class); }
    public function bookedBy() { return $this->belongsTo(User::class, 'booked_by'); }
    public function visit()    { return $this->hasOne(PatientVisit::class); }
    public function statusHistory() { return $this->hasMany(AppointmentStatusHistory::class); }

    public function isPending()   { return $this->status === 'pending'; }
    public function isConfirmed() { return $this->status === 'confirmed'; }
    public function isCompleted() { return $this->status === 'completed'; }
    public function isCancelled() { return $this->status === 'cancelled'; }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'amber',
            'confirmed'  => 'blue',
            'checked_in' => 'indigo',
            'in_progress'=> 'violet',
            'completed'  => 'green',
            'cancelled'  => 'red',
            'no_show'    => 'slate',
            'rescheduled'=> 'orange',
            default      => 'slate',
        };
    }

    public static function generateNumber(): string
    {
        return 'APT-' . strtoupper(uniqid());
    }
}
