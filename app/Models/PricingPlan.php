<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'monthly_price', 'yearly_price', 'is_active'];

    public function features() { return $this->hasMany(PlanFeature::class); }
}
