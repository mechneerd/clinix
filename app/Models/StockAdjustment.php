<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'medicine_id', 'quantity_adjusted', 'reason', 'notes', 'performed_by'
    ];

    public function medicine() { return $this->belongsTo(Medicine::class); }
    public function performer() { return $this->belongsTo(User::class, 'performed_by'); }
}
