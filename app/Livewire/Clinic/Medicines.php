<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Medicine;

class Medicines extends Component
{
    use WithPagination;

    public $pageTitle = 'Medicine Inventory';
    public $search = '';
    
    // Form fields
    public $medicineId = null;
    public $name = '';
    public $generic_name = '';
    public $category = '';
    public $dosage_form = '';
    public $strength = '';
    public $price = '';
    public $stock_quantity = '';
    public $reorder_level = 10;
    public $is_active = true;

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'generic_name' => 'nullable|string|max:255',
        'category' => 'required|string|max:100',
        'dosage_form' => 'required|string|max:100',
        'strength' => 'nullable|string|max:100',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'reorder_level' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $medicine = Medicine::where('clinic_id', auth()->user()->clinic->id)->findOrFail($id);
        $this->medicineId = $medicine->id;
        $this->name = $medicine->name;
        $this->generic_name = $medicine->generic_name;
        $this->category = $medicine->category;
        $this->dosage_form = $medicine->dosage_form;
        $this->strength = $medicine->strength;
        $this->price = $medicine->price;
        $this->stock_quantity = $medicine->stock_quantity;
        $this->reorder_level = $medicine->reorder_level;
        $this->is_active = $medicine->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'clinic_id' => auth()->user()->clinic->id,
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'category' => $this->category,
            'dosage_form' => $this->dosage_form,
            'strength' => $this->strength,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'reorder_level' => $this->reorder_level,
            'is_active' => $this->is_active,
        ];

        if ($this->medicineId) {
            Medicine::where('clinic_id', auth()->user()->clinic->id)
                ->findOrFail($this->medicineId)
                ->update($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Medicine updated successfully']);
        } else {
            Medicine::create($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Medicine added to inventory']);
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
        $medicine = Medicine::where('clinic_id', auth()->user()->clinic->id)->findOrFail($this->deleteId);
        $medicine->delete();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Medicine removed from inventory']);
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->medicineId = null;
        $this->name = '';
        $this->generic_name = '';
        $this->category = '';
        $this->dosage_form = '';
        $this->strength = '';
        $this->price = '';
        $this->stock_quantity = '';
        $this->reorder_level = 10;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $medicines = Medicine::where('clinic_id', auth()->user()->clinic->id)
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('generic_name', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.clinic.medicines', [
            'medicines' => $medicines
        ]);
    }
}
