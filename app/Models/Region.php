<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['country_id', 'name'];

    public function country() { return $this->belongsTo(Country::class); }
    public function subregions() { return $this->hasMany(Subregion::class); }
    public function cities() { return $this->hasMany(City::class); }
}
