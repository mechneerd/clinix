<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['clinic_id', 'name', 'department', 'capacity', 'is_active'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function rooms() { return $this->hasMany(Room::class); }
}
