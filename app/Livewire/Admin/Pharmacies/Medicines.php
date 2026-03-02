<?php

namespace App\Livewire\Admin\Pharmacies;

use App\Models\Clinic;
use App\Models\Medicine;
use App\Models\MedicineCategory;
use App\Models\Pharmacy;
use App\Services\PharmacyService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Medicines — Clinix')]
class Medicines extends Component
{
    use WithPagination;

    public Pharmacy $pharmacy;
    public Clinic   $clinic;
    public bool     $showForm     = false;
    public ?int     $editingId    = null;
    public ?int     $categoryFilter = null;
    public string   $search       = '';

    // Form
    public string  $name          = '';
    public ?int    $category_id   = null;
    public string  $generic_name  = '';
    public string  $brand_name    = '';
    public string  $code          = '';
    public string  $type          = 'tablet';
    public string  $category_type = 'medicine';
    public string  $strength      = '';
    public string  $unit          = '';
    public string  $manufacturer  = '';
    public int     $current_stock = 0;
    public int     $reorder_level = 10;
    public float   $purchase_price = 0;
    public float   $selling_price  = 0;
    public float   $mrp            = 0;
    public string  $batch_number  = '';
    public string  $expiry_date   = '';
    public bool    $is_active     = true;

    public function mount(int $clinicId, int $pharmacyId): void
    {
        $this->clinic   = Clinic::where('id', $clinicId)->where('owner_id', auth()->id())->firstOrFail();
        $this->pharmacy = Pharmacy::where('id', $pharmacyId)->where('clinic_id', $this->clinic->id)->firstOrFail();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['name','generic_name','brand_name','code','type','strength','unit','manufacturer',
            'current_stock','reorder_level','purchase_price','selling_price','mrp',
            'batch_number','expiry_date','category_id','editingId']);
        $this->is_active     = true;
        $this->category_type = 'medicine';
        $this->type          = 'tablet';
        $this->showForm      = true;
    }

    public function openEdit(int $id): void
    {
        $med = Medicine::findOrFail($id);
        $this->fill($med->only(['name','generic_name','brand_name','code','type','category_type','strength','unit',
            'manufacturer','current_stock','reorder_level','purchase_price','selling_price','mrp',
            'batch_number','is_active','category_id']));
        $this->expiry_date = $med->expiry_date?->format('Y-m-d') ?? '';
        $this->editingId   = $id;
        $this->showForm    = true;
    }

    public function save(PharmacyService $service): void
    {
        $this->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|string',
            'current_stock' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        $data = $this->only(['name','generic_name','brand_name','code','type','category_type','strength',
            'unit','manufacturer','current_stock','reorder_level','purchase_price','selling_price',
            'mrp','batch_number','expiry_date','category_id','is_active']);

        if (!$data['expiry_date']) unset($data['expiry_date']);

        if ($this->editingId) {
            $med = Medicine::findOrFail($this->editingId);
            $service->updateMedicine($med, $data);
        } else {
            $service->addMedicine($this->pharmacy, $data);
        }

        $this->showForm = false;
        $this->dispatch('toast', message: 'Medicine saved.');
    }

    public function render(PharmacyService $service)
    {
        $medicines  = $service->getMedicines($this->pharmacy->id, $this->categoryFilter, $this->search ?: null);
        $categories = MedicineCategory::where('pharmacy_id', $this->pharmacy->id)->get();
        $stats      = $service->getStats($this->pharmacy->id);

        return view('livewire.admin.pharmacies.medicines', compact('medicines','categories','stats'));
    }
}
