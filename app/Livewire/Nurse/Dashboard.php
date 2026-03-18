<?php

namespace App\Livewire\Nurse;

use Livewire\Component;

class Dashboard extends Component
{
    public $clinic;
    public $waitingPatients = 0;
    public $completedToday = 0;
    public $totalAppointments = 0;
    public $upcomingAppointments = [];
    public $weeklyStats = [];

    public function mount()
    {
        $staff = auth()->user()->staff;
        if (!$staff || $staff->role !== 'nurse') {
            return redirect()->route('shared.settings');
        }

        $this->clinic = $staff->clinic;
        $this->loadData();
    }

    public function loadData()
    {
        $clinicId = $this->clinic->id;

        $this->waitingPatients = \App\Models\Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->where('status', 'checked_in')
            ->count();

        $this->completedToday = \App\Models\Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();

        $this->totalAppointments = \App\Models\Appointment::where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->count();

        $this->upcomingAppointments = \App\Models\Appointment::with(['patient', 'doctor.user'])
            ->where('clinic_id', $clinicId)
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['scheduled', 'confirmed', 'checked_in'])
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        // Weekly stats for nurses (patient volume)
        $this->weeklyStats = collect(range(0, 6))->map(function($day) use ($clinicId) {
            $date = now()->subDays($day);
            return [
                'day' => $date->format('D'),
                'count' => \App\Models\Appointment::where('clinic_id', $clinicId)
                    ->whereDate('appointment_date', $date)
                    ->count()
            ];
        })->reverse()->values();
    }

    public function render()
    {
        return view('livewire.nurse.dashboard');
    }
}