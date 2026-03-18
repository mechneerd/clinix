<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = ['name', 'details', 'country'];

    public function brands() { return $this->hasMany(MedicineBrand::class); }
}
