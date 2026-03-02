<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prescription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'visit_id','clinic_id','patient_id','doctor_id',
        'prescription_number','clinical_notes','investigations_advised',
        'special_instructions','dietary_advice','follow_up_instructions',
        'follow_up_date','follow_up_days','is_finalized','finalized_at','finalized_by',
    ];
    protected $casts = [
        'follow_up_date' => 'date',
        'finalized_at'   => 'datetime',
        'is_finalized'   => 'boolean',
    ];

    public function visit()    { return $this->belongsTo(PatientVisit::class, 'visit_id'); }
    public function patient()  { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor()   { return $this->belongsTo(User::class, 'doctor_id'); }
    public function clinic()   { return $this->belongsTo(Clinic::class); }
    public function items()    { return $this->hasMany(PrescriptionItem::class); }

    public static function generateNumber(): string
    {
        return 'RX-' . strtoupper(uniqid());
    }
}
