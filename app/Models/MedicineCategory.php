<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function medicines() { return $this->hasMany(Medicine::class); }
}
