<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'clinic_id', 'name', 'asset_code', 'category', 
        'purchase_date', 'purchase_cost', 'current_value', 
        'warranty_expiry', 'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
