<?php

namespace App\Livewire\Subscription;

use App\Services\SubscriptionService;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Choose Your Plan — Clinix')]
class PackageSelection extends Component
{
    public Collection $tiers;
    public string $billing    = 'monthly';
    public ?int   $selectedId = null;
    public bool   $processing = false;

    public function mount(SubscriptionService $service): void
    {
        // Redirect if already subscribed
        if (auth()->user()->hasActiveSubscription()) {
            $this->redirect(route('admin.dashboard'), navigate: true);
        }

        $this->tiers = $service->getAvailablePlans();
    }

    public function selectPlan(int $tierId): void
    {
        $this->selectedId = $tierId;
    }

    public function confirmSelection(SubscriptionService $service): void
    {
        if (!$this->selectedId) {
            $this->addError('plan', 'Please select a plan to continue.');
            return;
        }

        $this->processing = true;

        try {
            $service->selectPlan(auth()->user(), $this->selectedId, $this->billing);
            $this->redirect(route('admin.dashboard'), navigate: true);
        } catch (\Exception $e) {
            $this->addError('plan', $e->getMessage());
            $this->processing = false;
        }
    }

    public function render()
    {
        return view('livewire.subscription.package-selection', [
            'tiers' => $this->tiers,
        ]);
    }
}