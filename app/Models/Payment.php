<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id','user_subscription_id','stripe_payment_intent_id',
        'stripe_invoice_id','amount','currency','status',
        'payment_method','paid_at','failure_reason','metadata',
    ];
    protected $casts = ['paid_at'=>'datetime','amount'=>'decimal:2','metadata'=>'array'];
    public function user()         { return $this->belongsTo(User::class); }
    public function subscription() { return $this->belongsTo(UserSubscription::class,'user_subscription_id'); }
}
