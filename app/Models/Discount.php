<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['clinic_id', 'name', 'type', 'value', 'valid_from', 'valid_until'];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
