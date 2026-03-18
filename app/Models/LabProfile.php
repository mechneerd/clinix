<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabProfile extends Model
{
    protected $fillable = ['clinic_id', 'name', 'code', 'description', 'base_price'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function tests() { return $this->hasMany(LabTest::class); }
}
