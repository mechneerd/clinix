<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['clinic_id','name','code','description','icon','sort_order','is_active'];
    protected $casts    = ['is_active' => 'boolean'];

    public function clinic()       { return $this->belongsTo(Clinic::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function doctors()      { return $this->hasManyThrough(User::class, StaffProfile::class, 'department_id', 'id', 'id', 'user_id'); }
}
