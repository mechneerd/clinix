<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabSample extends Model
{
    protected $fillable = [
        'lab_order_id', 'sample_barcode', 'sample_type', 
        'collected_at', 'collected_by', 'received_at', 
        'received_by', 'status'
    ];

    protected $casts = [
        'collected_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function labOrder() { return $this->belongsTo(LabOrder::class); }
    public function collector() { return $this->belongsTo(User::class, 'collected_by'); }
    public function receiver() { return $this->belongsTo(User::class, 'received_by'); }
}
