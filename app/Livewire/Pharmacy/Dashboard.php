<?php

namespace App\Livewire\Pharmacy;

use Livewire\Component;

class Dashboard extends Component
{
    public $clinic;
    public $pendingPrescriptions = 0;
    public $outOfStock = 0;
    public $lowStock = 0;
    public $productsDispensedToday = 0;
    public $availablePrescriptions = [];

    public function mount()
    {
        $staff = auth()->user()->staff;
        if (!$staff || !in_array($staff->role, ['pharmacy_manager', 'pharmacy_worker'])) {
            return redirect()->route('shared.settings');
        }

        $this->clinic = $staff->clinic;
        $this->loadData();
    }

    public function loadData()
    {
        $clinicId = $this->clinic->id;

        $this->pendingPrescriptions = \App\Models\Prescription::whereHas('medicalRecord', function($q) use ($clinicId) {
                $q->whereHas('doctor', function($sq) use ($clinicId) {
                    $sq->where('clinic_id', $clinicId);
                });
            })
            ->where('is_dispensed', false)
            ->count();

        $this->outOfStock = \App\Models\Medicine::where('clinic_id', $clinicId)
            ->where('stock_quantity', '<=', 0)
            ->count();

        $this->lowStock = \App\Models\Medicine::where('clinic_id', $clinicId)
            ->whereRaw('stock_quantity <= reorder_level')
            ->where('stock_quantity', '>', 0)
            ->count();

        $this->productsDispensedToday = \App\Models\Prescription::whereHas('medicalRecord', function($q) use ($clinicId) {
                $q->whereHas('doctor', function($sq) use ($clinicId) {
                    $sq->where('clinic_id', $clinicId);
                });
            })
            ->where('is_dispensed', true)
            ->whereDate('dispensed_at', today())
            ->count();

        $this->availablePrescriptions = \App\Models\Prescription::with(['medicalRecord.patient', 'medicalRecord.doctor.user'])
            ->whereHas('medicalRecord', function($q) use ($clinicId) {
                $q->whereHas('doctor', function($sq) use ($clinicId) {
                    $sq->where('clinic_id', $clinicId);
                });
            })
            ->where('is_dispensed', false)
            ->latest()
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.pharmacy.dashboard');
    }
}