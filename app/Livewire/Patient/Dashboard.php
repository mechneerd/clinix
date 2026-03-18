<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Models\Appointment;

class Dashboard extends Component
{
    public $patient;
    public $upcomingAppointments = [];
    public $recentPrescriptions = [];
    public $recentReports = [];

    public function mount()
    {
        $this->patient = auth()->user()->patient;
        $this->loadData();
    }

    public function loadData()
    {
        if (!$this->patient) return;

        $this->upcomingAppointments = Appointment::with(['clinic', 'doctor.user'])
            ->where('patient_id', $this->patient->id)
            ->where('appointment_date', '>=', today())
            ->whereIn('status', [
                Appointment::STATUS_PENDING, 
                Appointment::STATUS_CONFIRMED, 
                Appointment::STATUS_SCHEDULED,
                'checked_in'
            ])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();
    }

    public function setReminder($appointmentId, $minutes)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Security check
        if ($appointment->patient_id !== $this->patient->id) {
            return;
        }

        $appointment->update(['reminder_minutes' => $minutes]);
        $this->loadData();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Reminder set successfully.']);
    }

    public function startChatWithClinic($clinicId)
    {
        $clinic = \App\Models\Clinic::findOrFail($clinicId);
        $clinicAdminId = $clinic->user_id;

        if (!$clinicAdminId) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'This clinic does not have an active administrator account.']);
            return;
        }

        $conversation = auth()->user()->conversations()
            ->whereHas('users', fn($q) => $q->where('users.id', $clinicAdminId))
            ->first();

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'clinic_id' => $clinicId,
                'last_message_at' => now()
            ]);
            $conversation->users()->attach([auth()->id(), $clinicAdminId]);
        }

        return redirect()->route('messages', ['conversationId' => $conversation->id]);
    }

    public function render()
    {
        return view('livewire.patient.dashboard');
    }
}