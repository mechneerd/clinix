<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = ['clinic_id', 'recipient_email', 'subject', 'content', 'status'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
