<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientTransfer extends Model
{
    protected $fillable = [
        'patient_admission_id', 'from_room_id', 'to_room_id', 
        'reason', 'transferred_at', 'performed_by'
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];

    public function admission() { return $this->belongsTo(PatientAdmission::class, 'patient_admission_id'); }
    public function fromRoom() { return $this->belongsTo(Room::class, 'from_room_id'); }
    public function toRoom() { return $this->belongsTo(Room::class, 'to_room_id'); }
    public function performer() { return $this->belongsTo(User::class, 'performed_by'); }
}
