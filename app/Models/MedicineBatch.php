<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineBatch extends Model
{
    protected $fillable = [
        'medicine_id', 'batch_number', 'expiry_date', 
        'cost_price', 'selling_price', 'initial_quantity', 
        'current_quantity', 'supplier_id'
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function medicine() { return $this->belongsTo(Medicine::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
}
