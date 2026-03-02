<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionTier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description',
        'monthly_price', 'yearly_price',
        'is_active', 'sort_order',
        'max_clinics', 'max_labs', 'max_pharmacies',
        'max_doctors', 'max_nurses', 'max_admins',
        'max_managers', 'max_staff_total',
        'has_sms_notifications', 'has_email_notifications',
        'has_push_notifications', 'has_advanced_reports',
        'has_api_access', 'has_custom_branding',
        'has_priority_support', 'trial_days',
    ];

    protected $casts = [
        'monthly_price'           => 'decimal:2',
        'yearly_price'            => 'decimal:2',
        'is_active'               => 'boolean',
        'has_sms_notifications'   => 'boolean',
        'has_email_notifications' => 'boolean',
        'has_push_notifications'  => 'boolean',
        'has_advanced_reports'    => 'boolean',
        'has_api_access'          => 'boolean',
        'has_custom_branding'     => 'boolean',
        'has_priority_support'    => 'boolean',
    ];

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function isFree(): bool
    {
        return $this->monthly_price == 0;
    }

    public function getYearlySavingsAttribute(): float
    {
        return round(($this->monthly_price * 12) - $this->yearly_price, 2);
    }

    public function getYearlySavingsPercentAttribute(): int
    {
        if ($this->monthly_price == 0) return 0;
        return (int) round((($this->monthly_price * 12 - $this->yearly_price) / ($this->monthly_price * 12)) * 100);
    }

    public function features(): array
    {
        $list = [];

        $list[] = "Up to {$this->max_clinics} clinic(s)";

        if ($this->max_labs > 0)       $list[] = "Up to {$this->max_labs} lab(s)";
        if ($this->max_pharmacies > 0) $list[] = "Up to {$this->max_pharmacies} pharmacy(ies)";
        if ($this->max_doctors > 0)    $list[] = "Up to {$this->max_doctors} doctor(s)";
        if ($this->max_nurses > 0)     $list[] = "Up to {$this->max_nurses} nurse(s)";

        $list[] = "Up to {$this->max_staff_total} total staff";

        if ($this->has_email_notifications) $list[] = 'Email notifications';
        if ($this->has_sms_notifications)   $list[] = 'SMS notifications';
        if ($this->has_push_notifications)  $list[] = 'Push notifications';
        if ($this->has_advanced_reports)    $list[] = 'Advanced analytics & reports';
        if ($this->has_api_access)          $list[] = 'Full API access';
        if ($this->has_custom_branding)     $list[] = 'Custom branding';
        if ($this->has_priority_support)    $list[] = 'Priority support';
        if ($this->trial_days > 0)          $list[] = "{$this->trial_days}-day free trial";

        return $list;
    }
}
