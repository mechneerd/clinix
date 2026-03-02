<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_tier_id',
        'status',
        'billing_cycle',
        'starts_at',
        'ends_at',
        'trial_ends_at',
        'cancelled_at',
        'auto_renew',
        'stripe_subscription_id',
        'custom_limits',
    ];

    protected $casts = [
        'starts_at'     => 'datetime',
        'ends_at'       => 'datetime',
        'trial_ends_at' => 'datetime',
        'cancelled_at'  => 'datetime',
        'auto_renew'    => 'boolean',
        'custom_limits' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tier()
    {
        return $this->belongsTo(SubscriptionTier::class, 'subscription_tier_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }
}
