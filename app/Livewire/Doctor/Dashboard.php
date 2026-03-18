<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Prescription;

class Dashboard extends Component
{
    public $pageTitle = 'Doctor Dashboard';
    public $doctor;
    public $todayAppointments = [];
    public $patientsAttendedToday = 0;
    public $waitingPatients = 0;
    public $totalPatients = 0;
    public $pendingPrescriptions = 0;
    public $weeklyStats = [];
    public $recentActivity = [];

    public function mount()
    {
        $this->doctor = auth()->user()->staff;
        if (!$this->doctor) {
            return redirect()->route('shared.settings')->with('error', 'Profile incomplete.');
        }
        $this->loadData();
    }

    public function loadData()
    {
        $doctorId = $this->doctor->id;
        $clinicId = $this->doctor->clinic_id;
        
        $this->todayAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->orderBy('start_time')
            ->get();
            
        $this->patientsAttendedToday = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();

        $this->waitingPatients = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['checked_in', 'scheduled'])
            ->count();
            
        $this->totalPatients = Appointment::where('doctor_id', $doctorId)
            ->distinct('patient_id')
            ->count('patient_id');
            
        $this->pendingPrescriptions = Prescription::whereHas('medicalRecord', fn($q) => 
            $q->where('doctor_id', $doctorId)
        )->where('is_dispensed', false)->count();
        
        // Mocking recent clinical activity for "imagination"
        $this->recentActivity = Appointment::with(['patient', 'medicalRecord'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->latest()
            ->limit(5)
            ->get();

        // Weekly appointment stats
        $this->weeklyStats = collect(range(0, 6))->map(function($day) use ($doctorId) {
            $date = now()->subDays($day);
            return [
                'day' => $date->format('D'),
                'count' => Appointment::where('doctor_id', $doctorId)
                    ->whereDate('appointment_date', $date)
                    ->count()
            ];
        })->reverse()->values();
    }

    public function startConsultation($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $appointment->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);
        
        return redirect()->route('doctor.consultation', $appointmentId);
    }

    public function render()
    {
        return view('livewire.doctor.dashboard');
    }
}