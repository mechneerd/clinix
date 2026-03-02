<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'avatar',
        'date_of_birth',
        'gender',
        'blood_group',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'allergies',
        'preferences',
        // admin-specific
        'license_number',
        'specialty',
        'qualifications',
        'bio',
        'years_of_experience',
        'consultation_fee',
    ];

    protected $casts = [
        'preferences'    => 'array',
        'qualifications' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
