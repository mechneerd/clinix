<?php

namespace App\Livewire\Admin\Labs;

use App\Models\Clinic;
use App\Models\Lab;
use App\Services\LabManagementService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Labs — Clinix')]
class Index extends Component
{
    public Clinic $clinic;
    public bool   $showForm   = false;
    public ?int   $editingId  = null;

    // Form fields
    public string  $name         = '';
    public string  $description  = '';
    public string  $email        = '';
    public string  $phone        = '';
    public string  $address      = '';
    public bool    $is_active    = true;
    public array   $working_hours = [];

    public function mount(int $clinicId): void
    {
        $this->clinic = Clinic::where('id', $clinicId)->where('owner_id', auth()->id())->firstOrFail();
    }

    public function openCreate(): void
    {
        $this->reset(['name','description','email','phone','address','is_active','editingId']);
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $lab = Lab::where('id', $id)->where('clinic_id', $this->clinic->id)->firstOrFail();
        $this->fill($lab->only(['name','description','email','phone','address','is_active']));
        $this->editingId = $id;
        $this->showForm  = true;
    }

    public function save(LabManagementService $service): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = $this->only(['name','description','email','phone','address','is_active']);

        if ($this->editingId) {
            $lab = Lab::where('id', $this->editingId)->where('clinic_id', $this->clinic->id)->firstOrFail();
            $service->updateLab($lab, $data);
            $this->dispatch('toast', message: 'Lab updated.');
        } else {
            $service->createLab($this->clinic, $data);
            $this->dispatch('toast', message: 'Lab created.');
        }

        $this->showForm = false;
        $this->reset(['name','description','email','phone','address','editingId']);
    }

    public function deleteLab(int $id, LabManagementService $service): void
    {
        $lab = Lab::where('id', $id)->where('clinic_id', $this->clinic->id)->firstOrFail();
        $service->deleteLab($lab);
        $this->dispatch('toast', message: 'Lab deleted.');
    }

    public function render(LabManagementService $service)
    {
        $labs = $service->getClinicLabs($this->clinic->id);
        return view('livewire.admin.labs.index', compact('labs'));
    }
}
