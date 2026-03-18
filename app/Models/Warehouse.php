<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['clinic_id', 'name', 'location'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
