<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    protected $fillable = ['pricing_plan_id', 'feature_name', 'feature_slug', 'limit_value'];

    public function plan() { return $this->belongsTo(PricingPlan::class, 'pricing_plan_id'); }
}
