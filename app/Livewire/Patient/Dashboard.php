<?php

namespace App\Livewire\Patient;

use App\Services\AppointmentService;
use App\Services\LabService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

#[Layout('layouts.patient')]
#[Title('My Dashboard — Clinix')]
class Dashboard extends Component
{
    public array $notifications = [];

    public function mount(): void
    {
        $this->notifications = auth()->user()
            ->unreadNotifications
            ->take(5)
            ->toArray();
    }

    // Called by Reverb broadcast listener in blade
    #[On('echo-private:patient.{authId},appointment.booked')]
    public function onAppointmentBooked(array $data): void
    {
        $this->dispatch('notify', [
            'type'    => 'success',
            'title'   => 'Appointment Confirmed!',
            'message' => 'Your appointment ' . $data['appointment_number'] . ' is booked.',
        ]);
        $this->refreshNotifications();
    }

    #[On('echo-private:patient.{authId},appointment.status.updated')]
    public function onAppointmentStatusUpdated(array $data): void
    {
        $this->dispatch('notify', [
            'type'    => 'info',
            'title'   => 'Appointment Updated',
            'message' => 'Appointment ' . $data['number'] . ' is now ' . $data['new_status'],
        ]);
        $this->refreshNotifications();
    }

    #[On('echo-private:patient.{authId},lab.report.ready')]
    public function onLabReportReady(array $data): void
    {
        $this->dispatch('notify', [
            'type'    => 'success',
            'title'   => 'Lab Results Ready!',
            'message' => 'Your lab report ' . $data['order_number'] . ' is now available.',
        ]);
        $this->refreshNotifications();
    }

    public function refreshNotifications(): void
    {
        $this->notifications = auth()->user()
            ->fresh()
            ->unreadNotifications
            ->take(5)
            ->toArray();
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->notifications = [];
    }

    public function getAuthIdProperty(): int
    {
        return auth()->id();
    }

    public function render(AppointmentService $apptService, LabService $labService)
    {
        $user       = auth()->user()->load(['profile']);
        $upcoming   = $apptService->getUpcomingForPatient($user->id);
        $recentLabs = $labService->getPatientOrders($user->id)->take(3);

        $stats = [
            'total_appointments' => $user->appointments()->count(),
            'upcoming'           => $upcoming->count(),
            'lab_orders'         => $user->labOrders()->count(),
            'prescriptions'      => $user->prescriptions()->count(),
        ];

        return view('livewire.patient.dashboard', compact('user', 'upcoming', 'recentLabs', 'stats'));
    }
}