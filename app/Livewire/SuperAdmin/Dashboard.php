<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Clinic;
use App\Models\SubscriptionTier;
use App\Models\User;
use App\Models\UserSubscription;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.super-admin')]
#[Title('Platform Overview — Clinix')]
class Dashboard extends Component
{
    public function render()
    {
        $activeSubs = UserSubscription::where('status', 'active')->with('tier')->get();

        // Calculate MRR from tier prices since there's no price_paid column
        $mrr = $activeSubs->sum(function ($sub) {
            if (!$sub->tier) return 0;
            return $sub->billing_cycle === 'yearly'
                ? $sub->tier->yearly_price / 12
                : $sub->tier->monthly_price;
        });

        $totalRevenue = $activeSubs->sum(function ($sub) {
            if (!$sub->tier) return 0;
            return $sub->billing_cycle === 'yearly'
                ? $sub->tier->yearly_price
                : $sub->tier->monthly_price;
        });

        $stats = [
            'total_admins'   => User::where('registration_type', 'admin')->count(),
            'total_patients' => User::where('registration_type', 'patient')->count(),
            'total_clinics'  => Clinic::count(),
            'active_subs'    => $activeSubs->count(),
            'mrr'            => $mrr,
            'total_revenue'  => $totalRevenue,
        ];

        $recentAdmins = User::where('registration_type', 'admin')
            ->with(['activeSubscription.tier', 'clinics'])
            ->latest()
            ->take(8)
            ->get();

        $tiers = SubscriptionTier::withCount([
            'userSubscriptions' => fn ($q) => $q->where('status', 'active')
        ])->get();

        return view('livewire.super-admin.dashboard', compact('stats', 'recentAdmins', 'tiers'));
    }
}