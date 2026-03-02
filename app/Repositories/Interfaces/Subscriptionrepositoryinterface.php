<?php

namespace App\Repositories\Interfaces;

use App\Models\SubscriptionTier;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function getAllActiveTiers(): Collection;

    public function findTierById(int $id): ?SubscriptionTier;

    public function findTierBySlug(string $slug): ?SubscriptionTier;

    public function createUserSubscription(User $user, SubscriptionTier $tier, string $billing = 'monthly'): UserSubscription;

    public function getActiveSubscription(User $user): ?UserSubscription;
}