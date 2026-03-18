<?php

namespace App\Livewire\Reception;

use Livewire\Component;

class Dashboard extends Component
{
    public $clinic;
    public $totalToday = 0;
    public $checkedIn = 0;
    public $pendingInvoices = 0;
    public $walkIns = 0;
    public $pendingRequests = [];
    public $appointmentQueue = [];

    public function mount()
    {
        $staff = auth()->user()->staff;
        if (!$staff || !in_array($staff->role, ['reception_manager', 'receptionist'])) {
            return redirect()->route('shared.settings');
        }

        $this->clinic = $staff->clinic;
        $this->loadData();
    }

    public function loadData()
    {
        $clinicId = $this->clinic->id;

        $this->totalToday = \App\Models\Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->count();

        $this->checkedIn = \App\Models\Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->where('status', 'checked_in')
            ->count();

        $this->pendingInvoices = \App\Models\Invoice::where('clinic_id', $clinicId)
            ->where('status', 'unpaid')
            ->count();

        // Assuming walk-ins are recorded in appointments table or patient registration log
        $this->walkIns = \App\Models\Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->where('type', 'walk_in')
            ->count();

        $this->appointmentQueue = \App\Models\Appointment::with(['patient', 'doctor.user'])
            ->where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->where('status', '!=', \App\Models\Appointment::STATUS_PENDING)
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        $this->pendingRequests = \App\Models\Appointment::with(['patient', 'doctor.user'])
            ->where('clinic_id', $clinicId)
            ->where('status', \App\Models\Appointment::STATUS_PENDING)
            ->latest()
            ->limit(10)
            ->get();
    }

    public function confirm($id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        $appointment->update(['status' => \App\Models\Appointment::STATUS_CONFIRMED]);
        $this->loadData();
        session()->flash('success', 'Appointment for ' . $appointment->patient->full_name . ' confirmed successfully.');
    }

    public function cancel($id)
    {
        $appointment = \App\Models\Appointment::findOrFail($id);
        $appointment->update(['status' => \App\Models\Appointment::STATUS_CANCELLED]);
        $this->loadData();
        session()->flash('error', 'Appointment has been cancelled.');
    }

    public function render()
    {
        return view('livewire.reception.dashboard');
    }
}