<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'clinic_id', 'name', 'contact_person', 
        'email', 'phone', 'address', 'tax_number', 'status'
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function batches() { return $this->hasMany(MedicineBatch::class); }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
}
