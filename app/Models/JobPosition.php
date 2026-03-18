<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = ['clinic_id', 'title', 'description', 'min_salary', 'max_salary'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function staff() { return $this->hasMany(Staff::class); }
}
