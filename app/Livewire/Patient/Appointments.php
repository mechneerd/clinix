<?php

namespace App\Livewire\Patient;

use App\Services\AppointmentService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.patient')]
#[Title('My Appointments — Clinix')]
class Appointments extends Component
{
    use WithPagination;

    public string $statusFilter    = '';
    public ?int   $cancellingId    = null;
    public string $cancelReason    = '';
    public bool   $showCancelModal = false;

    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function openCancelModal(int $id): void
    {
        $this->cancellingId    = $id;
        $this->cancelReason    = '';
        $this->showCancelModal = true;
    }

    public function confirmCancel(AppointmentService $service): void
    {
        $this->validate(['cancelReason' => 'required|min:5']);

        $appointment = \App\Models\Appointment::where('id', $this->cancellingId)
            ->where('patient_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->firstOrFail();

        $service->cancelAppointment($appointment, $this->cancelReason);

        $this->showCancelModal = false;
        $this->cancellingId    = null;
        $this->cancelReason    = '';

        $this->dispatch('toast', message: 'Appointment cancelled successfully.');
    }

    public function render(AppointmentService $service)
    {
        $appointments = $service->getPatientAppointments(
            auth()->id(),
            $this->statusFilter ?: null
        );

        return view('livewire.patient.appointments', compact('appointments'));
    }
}