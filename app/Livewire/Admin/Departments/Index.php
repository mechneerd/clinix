<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Clinic;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Departments — Clinix')]
class Index extends Component
{
    public Clinic $clinic;
    public bool   $showForm  = false;
    public ?int   $editingId = null;

    public string $name        = '';
    public string $code        = '';
    public string $description = '';
    public string $icon        = 'building-office-2';
    public int    $sort_order  = 0;
    public bool   $is_active   = true;

    public function mount(int $clinicId): void
    {
        $this->clinic = Clinic::where('id', $clinicId)->where('owner_id', auth()->id())->firstOrFail();
    }

    public function openCreate(): void
    {
        $this->reset(['name','code','description','icon','sort_order','editingId']);
        $this->is_active = true;
        $this->icon      = 'building-office-2';
        $this->showForm  = true;
    }

    public function openEdit(int $id): void
    {
        $dept = Department::where('id', $id)->where('clinic_id', $this->clinic->id)->firstOrFail();
        $this->fill($dept->only(['name','code','description','icon','sort_order','is_active']));
        $this->editingId = $id;
        $this->showForm  = true;
    }

    public function save(): void
    {
        $this->validate(['name' => 'required|string|max:255']);

        $data = $this->only(['name','code','description','icon','sort_order','is_active']);

        if ($this->editingId) {
            Department::where('id', $this->editingId)->where('clinic_id', $this->clinic->id)->update($data);
            $this->dispatch('toast', message: 'Department updated.');
        } else {
            Department::create(['clinic_id' => $this->clinic->id, ...$data]);
            $this->dispatch('toast', message: 'Department created.');
        }

        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Department::where('id', $id)->where('clinic_id', $this->clinic->id)->delete();
        $this->dispatch('toast', message: 'Department deleted.');
    }

    public function toggleStatus(int $id): void
    {
        $dept = Department::where('id', $id)->where('clinic_id', $this->clinic->id)->firstOrFail();
        $dept->update(['is_active' => !$dept->is_active]);
    }

    public function render()
    {
        $departments = Department::where('clinic_id', $this->clinic->id)
            ->withCount('appointments')
            ->orderBy('sort_order')
            ->get();

        return view('livewire.admin.departments.index', compact('departments'));
    }
}
