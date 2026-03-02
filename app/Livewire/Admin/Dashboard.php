<?php

namespace App\Livewire\Admin;

use App\Models\Clinic;
use App\Services\SubscriptionService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Dashboard — Clinix')]
class Dashboard extends Component
{
    public function render(SubscriptionService $subscriptionService)
    {
        $user         = auth()->user();
        $subscription = $subscriptionService->getActiveSubscription($user);
        $clinics      = Clinic::where('owner_id', $user->id)->get();
        $clinicIds    = $clinics->pluck('id');

        $stats = [
            'total_clinics'      => $clinics->count(),
            'total_patients'     => \App\Models\User::where('registration_type', 'patient')->count(),
            'appointments_today' => \App\Models\Appointment::whereDate('appointment_date', today())
                ->whereIn('clinic_id', $clinicIds)
                ->count(),
            'monthly_revenue'    => 0, // Wire up to payments table when ready
        ];

        return view('livewire.admin.dashboard', compact('subscription', 'clinics', 'stats'));
    }
}
