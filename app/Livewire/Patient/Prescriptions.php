<?php

namespace App\Livewire\Patient;

use App\Models\Prescription;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.patient')]
#[Title('My Prescriptions — Clinix')]
class Prescriptions extends Component
{
    use WithPagination;

    public ?int $viewingId = null;

    public function viewPrescription(int $id): void { $this->viewingId = $id; }
    public function closePrescription(): void { $this->viewingId = null; }

    public function render()
    {
        $prescriptions = Prescription::with(['doctor', 'clinic', 'items'])
            ->where('patient_id', auth()->id())
            ->where('is_finalized', true)
            ->orderByDesc('created_at')
            ->paginate(10);

        $detail = $this->viewingId
            ? Prescription::with(['doctor', 'clinic', 'items.medicine', 'visit'])
                ->where('id', $this->viewingId)
                ->where('patient_id', auth()->id())
                ->first()
            : null;

        return view('livewire.patient.prescriptions', compact('prescriptions', 'detail'));
    }
}