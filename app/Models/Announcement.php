<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['clinic_id', 'title', 'content', 'target_role', 'expires_at', 'created_by'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
