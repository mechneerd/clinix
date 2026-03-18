<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabConsumable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id', 'name', 'description', 'unit', 'price', 
        'stock_quantity', 'reorder_level', 'is_active'
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
