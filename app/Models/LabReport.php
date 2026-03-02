<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReport extends Model
{
    protected $fillable = [
        'lab_order_id','report_number','file_path','summary',
        'generated_by','generated_at','is_printed','printed_at',
    ];
    protected $casts = ['generated_at' => 'datetime', 'printed_at' => 'datetime', 'is_printed' => 'boolean'];

    public function labOrder()    { return $this->belongsTo(LabOrder::class); }
    public function generatedBy() { return $this->belongsTo(User::class, 'generated_by'); }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }
}
