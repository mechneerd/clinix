<?php

namespace App\Services;

use App\Events\AppointmentBooked;
use App\Events\AppointmentStatusUpdated;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentStatusChangedNotification;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use Illuminate\Support\Collection;

class AppointmentService
{
    public function __construct(
        protected AppointmentRepositoryInterface $repo
    ) {}

    public function bookAppointment(array $data): Appointment
    {
        $data['booked_by'] = $data['booked_by'] ?? $data['patient_id'];
        $data['status']    = 'pending';

        $appointment = $this->repo->createAppointment($data);

        // Notify patient via mail + database
        $appointment->patient->notify(new AppointmentBookedNotification($appointment));

        // Broadcast via Reverb
        broadcast(new AppointmentBooked($appointment));

        return $appointment;
    }

    public function cancelAppointment(Appointment $appointment, string $reason): Appointment
    {
        $oldStatus   = $appointment->status;
        $appointment = $this->repo->cancelAppointment($appointment, $reason, auth()->id());

        // Notify patient
        $appointment->patient->notify(new AppointmentStatusChangedNotification($appointment, $oldStatus));

        // Broadcast
        broadcast(new AppointmentStatusUpdated($appointment, $oldStatus));

        return $appointment;
    }

    public function updateStatus(Appointment $appointment, string $status, ?string $reason = null): Appointment
    {
        $oldStatus   = $appointment->status;
        $appointment = $this->repo->updateStatus($appointment, $status, $reason);

        // Only notify patient on meaningful status changes
        if (in_array($status, ['confirmed', 'cancelled', 'completed', 'rescheduled'])) {
            $appointment->patient->notify(new AppointmentStatusChangedNotification($appointment, $oldStatus));
            broadcast(new AppointmentStatusUpdated($appointment, $oldStatus));
        }

        return $appointment;
    }

    public function getPatientAppointments(int $patientId, ?string $status = null)
    {
        return $this->repo->getPatientAppointments($patientId, $status);
    }

    public function getUpcomingForPatient(int $patientId): Collection
    {
        return $this->repo->getUpcomingForPatient($patientId);
    }

    public function getAvailableSlots(int $doctorId, int $clinicId, string $date): array
    {
        return $this->repo->getAvailableSlots($doctorId, $clinicId, $date);
    }

    public function getAvailableDoctors(int $clinicId): Collection
    {
        return User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))
            ->whereHas('staffProfile', fn($q) => $q->where('clinic_id', $clinicId))
            ->with(['staffProfile'])
            ->get();
    }
}
