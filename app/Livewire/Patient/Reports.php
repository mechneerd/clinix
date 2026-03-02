<?php

namespace App\Livewire\Patient;

use App\Models\PatientVisit;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.patient')]
#[Title('Health Reports — Clinix')]
class Reports extends Component
{
    use WithPagination;

    public ?int $viewingId = null;

    public function viewReport(int $id): void { $this->viewingId = $id; }
    public function closeReport(): void { $this->viewingId = null; }

    public function render()
    {
        $visits = PatientVisit::with(['doctor', 'appointment.clinic'])
            ->where('patient_id', auth()->id())
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->paginate(10);

        $detail = $this->viewingId
            ? PatientVisit::with(['doctor', 'appointment.clinic', 'prescriptions.items', 'labOrders.items.labTest'])
                ->where('id', $this->viewingId)
                ->where('patient_id', auth()->id())
                ->first()
            : null;

        return view('livewire.patient.reports', compact('visits', 'detail'));
    }
}