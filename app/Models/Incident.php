<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'clinic_id', 'title', 'description', 'severity', 
        'occurrence_time', 'reported_by', 'action_taken', 'status'
    ];

    protected $casts = [
        'occurrence_time' => 'datetime',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function reporter() { return $this->belongsTo(User::class, 'reported_by'); }
}
