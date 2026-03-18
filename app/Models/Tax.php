<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = ['clinic_id', 'name', 'rate_percent', 'is_active'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
