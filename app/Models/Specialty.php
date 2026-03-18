<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id', 'name', 'description', 'is_active'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
