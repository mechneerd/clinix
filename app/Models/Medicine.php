<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id', 'name', 'generic_name', 'category', 'dosage_form', 
        'strength', 'price', 'stock_quantity', 'reorder_level', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function batches() { return $this->hasMany(MedicineBatch::class); }
    public function medicineCategory() { return $this->belongsTo(MedicineCategory::class, 'category'); }
    public function brand() { return $this->belongsTo(MedicineBrand::class); }
}
