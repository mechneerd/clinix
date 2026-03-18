<?php

namespace App\Livewire\Clinic\Settings;

use Livewire\Component;
use App\Models\Package;
use App\Models\Clinic;
use Illuminate\Support\Facades\DB;

class Subscription extends Component
{
    public $pageTitle = 'Subscription Plans';
    public $currentClinic;
    public $packages;

    public function mount()
    {
        $this->currentClinic = auth()->user()->clinic;
        $this->packages = Package::where('is_active', true)->where('is_approved', true)->get();
    }

    public function buyPackage($packageId)
    {
        $package = Package::findOrFail($packageId);
        $user = auth()->user();

        DB::transaction(function () use ($package, $user) {
            $clinic = $user->clinic;
            
            if (!$clinic) {
                // If no clinic yet, we'll create a placeholder or redirect to setup
                // For now let's assume setup comes after or during.
                // Re-reading user request: "admin need to create clinic then only admin can add..."
                // So maybe clinic creation is first?
                // But user also said "clinic admin will buy any of the package avaible"
                // Let's ensure clinic exists or create a basic one if buying first.
                $clinic = Clinic::create([
                    'user_id' => $user->id,
                    'name' => $user->name . "'s Clinic",
                    'slug' => str($user->name)->slug() . '-' . rand(100, 999),
                    'email' => $user->email,
                    'phone' => $user->phone ?? '0000000000',
                    'address' => 'Update your clinic address',
                    'city' => 'Update your city',
                    'state' => 'Update your state',
                    'country' => 'Update your country',
                    'status' => 'active',
                ]);
            }

            $clinic->update([
                'package_id' => $package->id,
                'package_expires_at' => now()->addDays($package->duration_days),
                'status' => 'active',
            ]);
        });

        $this->dispatch('toast', ['type' => 'success', 'message' => 'Package ' . $package->name . ' activated successfully!']);
        return redirect()->route('clinic.dashboard');
    }

    public function render()
    {
        return view('livewire.clinic.settings.subscription');
    }
}
