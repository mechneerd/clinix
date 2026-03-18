<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LabConsumable;

class LabConsumables extends Component
{
    use WithPagination;

    public $pageTitle = 'Lab Consumables Inventory';
    public $search = '';
    
    // Form fields
    public $consumableId = null;
    public $name = '';
    public $description = '';
    public $unit = '';
    public $price = '';
    public $stock_quantity = '';
    public $reorder_level = 5;
    public $is_active = true;

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'unit' => 'required|string|max:50',
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
        $item = LabConsumable::where('clinic_id', auth()->user()->clinic->id)->findOrFail($id);
        $this->consumableId = $item->id;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->unit = $item->unit;
        $this->price = $item->price;
        $this->stock_quantity = $item->stock_quantity;
        $this->reorder_level = $item->reorder_level;
        $this->is_active = $item->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'clinic_id' => auth()->user()->clinic->id,
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'reorder_level' => $this->reorder_level,
            'is_active' => $this->is_active,
        ];

        if ($this->consumableId) {
            LabConsumable::where('clinic_id', auth()->user()->clinic->id)
                ->findOrFail($this->consumableId)
                ->update($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Inventory item updated successfully']);
        } else {
            LabConsumable::create($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'New item added to lab inventory']);
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
        $item = LabConsumable::where('clinic_id', auth()->user()->clinic->id)->findOrFail($this->deleteId);
        $item->delete();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Item removed from inventory']);
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->consumableId = null;
        $this->name = '';
        $this->description = '';
        $this->unit = '';
        $this->price = '';
        $this->stock_quantity = '';
        $this->reorder_level = 5;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $items = LabConsumable::where('clinic_id', auth()->user()->clinic->id)
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.clinic.lab-consumables', [
            'consumables' => $items
        ]);
    }
}
