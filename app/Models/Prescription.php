<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id', 'prescription_no', 'prescribed_date', 
        'notes', 'is_dispensed', 'dispensed_at', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'prescribed_date' => 'date',
        'is_dispensed' => 'boolean',
        'dispensed_at' => 'datetime',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
}
