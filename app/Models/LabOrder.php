<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number','lab_id','clinic_id','patient_id','doctor_id','visit_id',
        'priority','clinical_notes','diagnosis','status',
        'total_amount','discount','net_amount','payment_status',
        'ordered_at','sample_collected_at','sample_collected_by',
        'completed_at','completed_by',
    ];

    protected $casts = [
        'ordered_at'          => 'datetime',
        'sample_collected_at' => 'datetime',
        'completed_at'        => 'datetime',
        'total_amount'        => 'decimal:2',
        'net_amount'          => 'decimal:2',
    ];

    public function patient()  { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor()   { return $this->belongsTo(User::class, 'doctor_id'); }
    public function lab()      { return $this->belongsTo(Lab::class); }
    public function clinic()   { return $this->belongsTo(Clinic::class); }
    public function items()    { return $this->hasMany(LabOrderItem::class); }
    public function report()   { return $this->hasOne(LabReport::class); }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'ordered'          => 'blue',
            'sample_collected' => 'indigo',
            'sample_received'  => 'violet',
            'in_progress'      => 'amber',
            'completed'        => 'green',
            'cancelled'        => 'red',
            default            => 'slate',
        };
    }

    public static function generateNumber(): string
    {
        return 'LAB-' . strtoupper(uniqid());
    }
}
