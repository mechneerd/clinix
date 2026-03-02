<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lab extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clinic_id','user_subscription_id','name','description',
        'email','phone','address','working_hours','is_active',
    ];
    protected $casts = ['working_hours' => 'array', 'is_active' => 'boolean'];

    public function clinic()  { return $this->belongsTo(Clinic::class); }
    public function tests()   { return $this->hasMany(LabTest::class); }
    public function orders()  { return $this->hasMany(LabOrder::class); }
}
