<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = ['clinic_id', 'recipient_number', 'message', 'provider_reference', 'status'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
