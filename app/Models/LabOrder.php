<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id', 'patient_id', 'doctor_id', 'appointment_id', 
        'order_no', 'status', 'total_amount', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(LabOrderItem::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Staff::class, 'doctor_id');
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
