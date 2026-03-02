<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Storage;

class ClinicService
{
    public function getAdminClinics(int $ownerId)
    {
        return Clinic::where('owner_id', $ownerId)
            ->withCount(['staff', 'departments', 'labs', 'pharmacies', 'appointments'])
            ->latest()
            ->get();
    }

    public function createClinic(array $data, User $owner): Clinic
    {
        $subscription = $owner->activeSubscription;

        if ($data['logo'] ?? false) {
            $data['logo'] = $data['logo']->store('clinics/logos', 'public');
        }

        return Clinic::create([
            ...$data,
            'owner_id'             => $owner->id,
            'user_subscription_id' => $subscription->id,
        ]);
    }

    public function updateClinic(Clinic $clinic, array $data): Clinic
    {
        if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
            if ($clinic->logo) Storage::disk('public')->delete($clinic->logo);
            $data['logo'] = $data['logo']->store('clinics/logos', 'public');
        }

        $clinic->update($data);
        return $clinic->fresh();
    }

    public function deleteClinic(Clinic $clinic): void
    {
        $clinic->delete();
    }

    public function toggleStatus(Clinic $clinic): Clinic
    {
        $clinic->update(['is_active' => !$clinic->is_active]);
        return $clinic->fresh();
    }

    public function canCreateMoreClinics(User $owner): bool
    {
        $sub  = $owner->activeSubscription?->load('tier');
        if (!$sub) return false;
        $max  = $sub->tier->max_clinics ?? 1;
        $curr = Clinic::where('owner_id', $owner->id)->count();
        return $curr < $max;
    }
}
