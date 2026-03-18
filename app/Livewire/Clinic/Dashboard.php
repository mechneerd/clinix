<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\Invoice;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $pageTitle = 'Clinic Dashboard';
    public $clinic;
    public $stats = [];
    public $todayAppointments = [];
    public $recentPatients = [];
    public $staffStats = [];

    public function mount()
    {
        $this->clinic = auth()->user()->clinic;
        $this->loadStats();
        $this->loadTodayAppointments();
        $this->loadRecentPatients();
        $this->loadStaffStats();
    }

    public function loadStats()
    {
        $clinicId = $this->clinic->id;
        
        $this->stats = [
            'total_patients' => Patient::whereHas('clinics', fn($q) => $q->where('clinic_id', $clinicId))->count(),
            'today_appointments' => Appointment::where('clinic_id', $clinicId)->today()->count(),
            'pending_appointments' => Appointment::where('clinic_id', $clinicId)->where('status', 'scheduled')->count(),
            'total_staff' => Staff::where('clinic_id', $clinicId)->count(),
            'monthly_revenue' => Invoice::where('clinic_id', $clinicId)->whereMonth('created_at', now()->month)->sum('total_amount'),
            'checked_in' => Appointment::where('clinic_id', $clinicId)->today()->whereNotNull('checked_in_at')->count(),
        ];
    }

    public function loadTodayAppointments()
    {
        $this->todayAppointments = Appointment::with(['patient', 'doctor.user'])
            ->where('clinic_id', $this->clinic->id)
            ->today()
            ->orderBy('start_time')
            ->get();
    }

    public function loadRecentPatients()
    {
        $this->recentPatients = Patient::whereHas('clinics', fn($q) => $q->where('clinic_id', $this->clinic->id))
            ->latest()
            ->take(5)
            ->get();
    }

    public function loadStaffStats()
    {
        $this->staffStats = Staff::where('clinic_id', $this->clinic->id)
            ->selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();
    }

    public function checkIn($appointmentId)
    {
        Appointment::findOrFail($appointmentId)->update([
            'checked_in_at' => now(),
            'status' => 'checked_in'
        ]);
        
        $this->dispatch('toast', type: 'success', message: 'Patient checked in successfully');
        $this->loadTodayAppointments();
        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.clinic.dashboard');
    }
}