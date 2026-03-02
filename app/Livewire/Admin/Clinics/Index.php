<?php

namespace App\Livewire\Admin\Clinics;

use App\Services\ClinicService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('My Clinics — Clinix')]
class Index extends Component
{
    public bool $showDeleteModal = false;
    public ?int $deletingId      = null;

    public function confirmDelete(int $id): void
    {
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    public function delete(ClinicService $service): void
    {
        $clinic = \App\Models\Clinic::where('id', $this->deletingId)
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        $service->deleteClinic($clinic);
        $this->showDeleteModal = false;
        $this->dispatch('toast', message: 'Clinic deleted.');
    }

    public function toggleStatus(int $id, ClinicService $service): void
    {
        $clinic = \App\Models\Clinic::where('id', $id)->where('owner_id', auth()->id())->firstOrFail();
        $service->toggleStatus($clinic);
    }

    public function render(ClinicService $service)
    {
        $clinics    = $service->getAdminClinics(auth()->id());
        $canCreate  = $service->canCreateMoreClinics(auth()->user());

        return view('livewire.admin.clinics.index', compact('clinics', 'canCreate'));
    }
}
