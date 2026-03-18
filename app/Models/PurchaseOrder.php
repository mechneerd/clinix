<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'clinic_id', 'supplier_id', 'po_number', 
        'order_date', 'expected_delivery_date', 
        'total_amount', 'status', 'created_by', 'notes'
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
}
