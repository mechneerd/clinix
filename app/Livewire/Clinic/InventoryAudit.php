<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockMovement;
use App\Models\Medicine;
use App\Models\LabConsumable;

class InventoryAudit extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $resourceType = 'all'; // all, medicine, consumable

    public function updatingSearch() { $this->resetPage(); }
    public function updatingType() { $this->resetPage(); }
    public function updatingResourceType() { $this->resetPage(); }

    public function render()
    {
        $movements = StockMovement::with(['stockable', 'creator'])
            ->whereHas('creator.staff', function($q) {
                $q->where('clinic_id', auth()->user()->staff->clinic_id);
            })
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->when($this->resourceType !== 'all', function($q) {
                $type = $this->resourceType === 'medicine' ? Medicine::class : LabConsumable::class;
                $q->where('stockable_type', $type);
            })
            ->when($this->search, function($q) {
                $q->where('reference_id', 'like', '%' . $this->search . '%')
                  ->orWhere('notes', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(15);

        // Summary Stats
        $stats = [
            'total_in' => StockMovement::where('type', 'purchase')->sum('quantity'),
            'total_out' => abs(StockMovement::whereIn('type', ['dispense', 'damaged'])->sum('quantity')),
            'low_stock_medicines' => Medicine::where('clinic_id', auth()->user()->staff->clinic_id)
                ->whereRaw('stock_quantity <= reorder_level')
                ->count(),
        ];

        return view('livewire.clinic.inventory-audit', [
            'movements' => $movements,
            'stats' => $stats
        ]);
    }
}
