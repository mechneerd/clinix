<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicSetting extends Model
{
    protected $fillable = ['clinic_id', 'key', 'value', 'type'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
