<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineBrand extends Model
{
    protected $fillable = ['name', 'manufacturer_id'];

    public function manufacturer() { return $this->belongsTo(Manufacturer::class); }
    public function medicines() { return $this->hasMany(Medicine::class); }
}
