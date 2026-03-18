<?php

namespace App\Livewire\Lab;

use Livewire\Component;

class Dashboard extends Component
{
    public $clinic;
    public $pendingTests = 0;
    public $criticalResults = 0;
    public $collectedToday = 0;
    public $lowStockConsumables = 0;
    public $activeOrders = [];

    public function mount()
    {
        $staff = auth()->user()->staff;
        if (!$staff || !in_array($staff->role, ['lab_manager', 'lab_worker'])) {
            return redirect()->route('shared.settings');
        }

        $this->clinic = $staff->clinic;
        $this->loadData();
    }

    public function loadData()
    {
        $clinicId = $this->clinic->id;

        $this->pendingTests = \App\Models\LabOrderItem::whereHas('labOrder', function($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            })
            ->where('result_status', 'pending')
            ->count();

        $this->criticalResults = \App\Models\LabOrderItem::whereHas('labOrder', function($q) use ($clinicId) {
                $q->where('clinic_id', $clinicId);
            })
            ->where('result_status', 'critical')
            ->count();

        $this->collectedToday = \App\Models\LabOrder::where('clinic_id', $clinicId)
            ->whereDate('created_at', today())
            ->count();
            
        $this->lowStockConsumables = \App\Models\LabConsumable::where('clinic_id', $clinicId)
            ->whereRaw('stock_quantity <= reorder_level')
            ->count();

        $this->activeOrders = \App\Models\LabOrder::with(['patient', 'doctor.user'])
            ->where('clinic_id', $clinicId)
            ->whereIn('status', ['pending', 'in_progress'])
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.lab.dashboard');
    }
}