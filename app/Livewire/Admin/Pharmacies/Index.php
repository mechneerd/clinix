<?php

namespace App\Livewire\Admin\Pharmacies;

use App\Models\Clinic;
use App\Models\Pharmacy;
use App\Services\PharmacyService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pharmacies — Clinix')]
class Index extends Component
{
    public Clinic $clinic;
    public bool   $showForm  = false;
    public ?int   $editingId = null;

    public string $name        = '';
    public string $description = '';
    public string $email       = '';
    public string $phone       = '';
    public string $address     = '';
    public bool   $is_active   = true;

    public function mount(int $clinicId): void
    {
        $this->clinic = Clinic::where('id', $clinicId)->where('owner_id', auth()->id())->firstOrFail();
    }

    public function openCreate(): void
    {
        $this->reset(['name','description','email','phone','address','is_active','editingId']);
        $this->is_active = true;
        $this->showForm  = true;
    }

    public function openEdit(int $id): void
    {
        $pharmacy = Pharmacy::where('id', $id)->where('clinic_id', $this->clinic->id)->firstOrFail();
        $this->fill($pharmacy->only(['name','description','email','phone','address','is_active']));
        $this->editingId = $id;
        $this->showForm  = true;
    }

    public function save(PharmacyService $service): void
    {
        $this->validate(['name' => 'required|string|max:255', 'phone' => 'nullable|string|max:20']);

        $data = $this->only(['name','description','email','phone','address','is_active']);

        if ($this->editingId) {
            $pharmacy = Pharmacy::where('id', $this->editingId)->where('clinic_id', $this->clinic->id)->firstOrFail();
            $service->updatePharmacy($pharmacy, $data);
        } else {
            $service->createPharmacy($this->clinic, $data);
        }

        $this->showForm = false;
        $this->dispatch('toast', message: 'Pharmacy saved.');
    }

    public function render(PharmacyService $service)
    {
        $pharmacies = $service->getClinicPharmacies($this->clinic->id);
        return view('livewire.admin.pharmacies.index', compact('pharmacies'));
    }
}
