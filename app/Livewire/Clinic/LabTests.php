<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LabTest;

class LabTests extends Component
{
    use WithPagination;

    public $pageTitle = 'Lab Test Management';
    public $search = '';
    
    // Form fields
    public $testId = null;
    public $name = '';
    public $code = '';
    public $description = '';
    public $price = '';
    public $normal_range = '';
    public $is_active = true;

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'normal_range' => 'nullable|string|max:255',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $test = LabTest::where('clinic_id', auth()->user()->clinic->id)->findOrFail($id);
        $this->testId = $test->id;
        $this->name = $test->name;
        $this->code = $test->code;
        $this->description = $test->description;
        $this->price = $test->price;
        $this->normal_range = $test->normal_range;
        $this->is_active = $test->is_active;
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
            'price' => $this->price,
            'normal_range' => $this->normal_range,
            'is_active' => $this->is_active,
        ];

        if ($this->testId) {
            LabTest::where('clinic_id', auth()->user()->clinic->id)
                ->findOrFail($this->testId)
                ->update($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Lab test updated successfully']);
        } else {
            LabTest::create($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Lab test created successfully']);
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
        $test = LabTest::where('clinic_id', auth()->user()->clinic->id)->findOrFail($this->deleteId);
        $test->delete();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Lab test deleted successfully']);
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->testId = null;
        $this->name = '';
        $this->code = '';
        $this->description = '';
        $this->price = '';
        $this->normal_range = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $tests = LabTest::where('clinic_id', auth()->user()->clinic->id)
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.clinic.lab-tests', [
            'tests' => $tests
        ]);
    }
}
