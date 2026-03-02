<?php

namespace App\Livewire\Admin\Labs;

use App\Models\Clinic;
use App\Models\Lab;
use App\Models\LabTest;
use App\Services\LabManagementService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Lab Tests — Clinix')]
class Tests extends Component
{
    use WithPagination;

    public Lab    $lab;
    public Clinic $clinic;
    public bool   $showForm    = false;
    public ?int   $editingId   = null;
    public ?int   $categoryFilter = null;

    // Form
    public string  $name         = '';
    public ?int    $category_id  = null;
    public string  $code         = '';
    public string  $description  = '';
    public string  $preparation_instructions = '';
    public float   $price        = 0;
    public string  $sample_type  = '';
    public int     $default_turnaround_time = 24;
    public string  $result_type  = 'text';
    public string  $unit         = '';
    public string  $normal_values = '';
    public bool    $is_active    = true;

    public function mount(int $clinicId, int $labId): void
    {
        $this->clinic = Clinic::where('id', $clinicId)->where('owner_id', auth()->id())->firstOrFail();
        $this->lab    = Lab::where('id', $labId)->where('clinic_id', $this->clinic->id)->firstOrFail();
    }

    public function openCreate(): void
    {
        $this->reset(['name','category_id','code','description','price','sample_type','result_type','unit','normal_values','is_active','editingId']);
        $this->is_active = true;
        $this->showForm  = true;
    }

    public function openEdit(int $id): void
    {
        $test = LabTest::findOrFail($id);
        $this->fill($test->only(['name','category_id','code','description','price','sample_type','default_turnaround_time','result_type','unit','normal_values','preparation_instructions','is_active']));
        $this->editingId = $id;
        $this->showForm  = true;
    }

    public function save(LabManagementService $service): void
    {
        $this->validate([
            'name'   => 'required|string|max:255',
            'price'  => 'required|numeric|min:0',
            'result_type' => 'required|string',
        ]);

        $data = $this->only(['name','category_id','code','description','price','sample_type','default_turnaround_time','result_type','unit','normal_values','preparation_instructions','is_active']);

        if ($this->editingId) {
            $test = LabTest::findOrFail($this->editingId);
            $service->updateTest($test, $data);
        } else {
            $service->createTest($this->lab, $data);
        }

        $this->showForm = false;
        $this->dispatch('toast', message: 'Test saved.');
    }

    public function render(LabManagementService $service)
    {
        $tests      = $service->getLabTests($this->lab->id, $this->categoryFilter);
        $categories = $service->getCategories($this->lab->id);

        return view('livewire.admin.labs.tests', compact('tests', 'categories'));
    }
}
