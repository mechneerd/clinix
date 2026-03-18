<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['region_id', 'subregion_id', 'name'];

    public function region() { return $this->belongsTo(Region::class); }
    public function subregion() { return $this->belongsTo(Subregion::class); }
    public function areas() { return $this->hasMany(Area::class); }
}
