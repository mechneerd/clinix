<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'stockable_id', 'stockable_type', 'type', 'quantity', 
        'reference_id', 'created_by', 'notes'
    ];

    public function stockable() { return $this->morphTo(); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
