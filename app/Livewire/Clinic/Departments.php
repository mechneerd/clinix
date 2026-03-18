<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Department;

class Departments extends Component
{
    use WithPagination;

    public $pageTitle = 'Manage Departments';
    public $search = '';
    
    // Form fields
    public $departmentId = null;
    public $name = '';
    public $code = '';
    public $description = '';
    public $is_active = true;

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $department = Department::where('clinic_id', auth()->user()->clinic->id)->findOrFail($id);
        $this->departmentId = $department->id;
        $this->name = $department->name;
        $this->code = $department->code;
        $this->description = $department->description;
        $this->is_active = $department->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'clinic_id' => auth()->user()->clinic->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        if ($this->departmentId) {
            Department::where('clinic_id', auth()->user()->clinic->id)
                ->findOrFail($this->departmentId)
                ->update($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Department updated successfully']);
        } else {
            Department::create($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Department created successfully']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $department = Department::where('clinic_id', auth()->user()->clinic->id)->findOrFail($this->deleteId);
        
        // Basic check if it has staff or dependencies (optional but good practice)
        if ($department->staff()->count() > 0) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'Cannot delete department with active staff members']);
            $this->showDeleteModal = false;
            return;
        }

        $department->delete();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Department deleted successfully']);
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->departmentId = null;
        $this->name = '';
        $this->code = '';
        $this->description = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $departments = Department::where('clinic_id', auth()->user()->clinic->id)
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(10);

        return view('livewire.clinic.departments', [
            'departments' => $departments
        ]);
    }
}
