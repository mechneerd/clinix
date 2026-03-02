<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pharmacy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clinic_id', 'user_subscription_id', 'name', 'description',
        'email', 'phone', 'address', 'working_hours', 'is_active',
    ];

    protected $casts = [
        'working_hours' => 'array',
        'is_active'     => 'boolean',
    ];

    public function clinic()        { return $this->belongsTo(Clinic::class); }
    public function medicines()     { return $this->hasMany(Medicine::class); }
    public function sales()         { return $this->hasMany(PharmacySale::class); }
    public function categories()    { return $this->hasMany(MedicineCategory::class); }
    public function staff()         { return $this->hasMany(StaffProfile::class, 'clinic_id', 'clinic_id'); }
}
