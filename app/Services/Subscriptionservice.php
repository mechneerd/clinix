<?php

namespace App\Services;

use App\Models\SubscriptionTier;
use App\Models\User;
use App\Models\UserSubscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Support\Collection;

class SubscriptionService
{
    public function __construct(
        protected SubscriptionRepositoryInterface $subscriptionRepository
    ) {}

    public function getAvailablePlans(): Collection
    {
        return $this->subscriptionRepository->getAllActiveTiers();
    }

    public function selectPlan(User $user, int $tierId, string $billing = 'monthly'): UserSubscription
    {
        $tier = $this->subscriptionRepository->findTierById($tierId);

        if (!$tier || !$tier->is_active) {
            throw new \InvalidArgumentException('Selected plan is not available.');
        }

        return $this->subscriptionRepository->createUserSubscription($user, $tier, $billing);
    }

    public function getActiveSubscription(User $user): ?UserSubscription
    {
        return $this->subscriptionRepository->getActiveSubscription($user);
    }

    public function hasFeature(User $user, string $feature): bool
    {
        $sub = $this->getActiveSubscription($user);
        if (!$sub || !$sub->tier) return false;

        return (bool) $sub->tier->{$feature};
    }
}
