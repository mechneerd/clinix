<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = ['payment_id', 'amount', 'reason', 'status', 'processed_by'];

    public function payment() { return $this->belongsTo(Payment::class); }
    public function processor() { return $this->belongsTo(User::class, 'processed_by'); }
}
