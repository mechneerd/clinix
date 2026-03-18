<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'price', 'billing_cycle', 'duration_days', 'is_active', 'is_approved',
        'max_clinics', 'max_labs', 'max_doctors', 'max_staff', 'max_patients_per_month',
        'storage_limit_mb', 'api_access', 'white_label', 'advanced_reporting',
        'sms_notifications', 'telemedicine'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_approved' => 'boolean',
        'api_access' => 'boolean',
        'white_label' => 'boolean',
        'advanced_reporting' => 'boolean',
        'sms_notifications' => 'boolean',
        'telemedicine' => 'boolean',
    ];

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }
}