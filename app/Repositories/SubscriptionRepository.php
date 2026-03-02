<?php

namespace App\Repositories;

use App\Models\SubscriptionTier;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function getAllActiveTiers(): Collection
    {
        return SubscriptionTier::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('monthly_price')
            ->get();
    }

    public function findTierById(int $id): ?SubscriptionTier
    {
        return SubscriptionTier::find($id);
    }

    public function findTierBySlug(string $slug): ?SubscriptionTier
    {
        return SubscriptionTier::where('slug', $slug)->first();
    }

    public function createUserSubscription(User $user, SubscriptionTier $tier, string $billing = 'monthly'): UserSubscription
    {
        return DB::transaction(function () use ($user, $tier, $billing) {
            // Cancel existing active subscription if any
            UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled', 'cancelled_at' => now()]);

            $endsAt = $billing === 'yearly' ? now()->addYear() : now()->addMonth();

            $trialEndsAt = ($tier->trial_days > 0 && !$tier->isFree())
                ? now()->addDays($tier->trial_days)
                : null;

            return UserSubscription::create([
                'user_id'              => $user->id,
                'subscription_tier_id' => $tier->id,
                'status'               => 'active',
                'billing_cycle'        => $billing,
                'starts_at'            => now(),
                'ends_at'              => $endsAt,
                'trial_ends_at'        => $trialEndsAt,
                'auto_renew'           => true,
            ]);
        });
    }

    public function getActiveSubscription(User $user): ?UserSubscription
    {
        return $user->activeSubscription()->with('tier')->first();
    }
}