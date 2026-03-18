<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id', 'name', 'code', 'description', 'is_active'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function wards() { return $this->hasMany(Ward::class); }
    public function requisitions() { return $this->hasMany(Requisition::class, 'requesting_department_id'); }
}
