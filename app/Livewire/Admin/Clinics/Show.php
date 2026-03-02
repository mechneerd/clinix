<?php

namespace App\Livewire\Admin\Clinics;

use App\Models\Clinic;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Clinic Overview — Clinix')]
class Show extends Component
{
    public Clinic $clinic;

    public function mount(int $id): void
    {
        $this->clinic = Clinic::where('id', $id)
            ->where('owner_id', auth()->id())
            ->withCount(['staff', 'departments', 'labs', 'pharmacies'])
            ->firstOrFail();
    }

    public function render()
    {
        $this->clinic->loadCount(['staff','departments','labs','pharmacies','appointments']);
        $todayAppointments = $this->clinic->appointments()
            ->whereDate('appointment_date', today())
            ->with('patient','doctor')
            ->limit(5)
            ->get();

        $stats = [
            'appointments_today'  => $this->clinic->appointments()->whereDate('appointment_date', today())->count(),
            'appointments_month'  => $this->clinic->appointments()->whereMonth('appointment_date', now()->month)->count(),
            'total_patients'      => $this->clinic->appointments()->distinct('patient_id')->count('patient_id'),
            'pending'             => $this->clinic->appointments()->where('status','pending')->count(),
        ];

        return view('livewire.admin.clinics.show', compact('todayAppointments', 'stats'));
    }
}
