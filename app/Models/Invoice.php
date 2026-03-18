<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id', 'patient_id', 'invoice_no', 'subtotal', 
        'tax_amount', 'discount_amount', 'total_amount', 'paid_amount', 'status'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
