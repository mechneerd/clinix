<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    protected $fillable = ['room_id', 'bed_number', 'type', 'status', 'daily_rate'];

    public function room() { return $this->belongsTo(Room::class); }
}
