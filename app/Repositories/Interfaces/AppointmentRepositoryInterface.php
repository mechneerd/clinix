<?php

namespace App\Repositories\Interfaces;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AppointmentRepositoryInterface
{
    public function createAppointment(array $data): Appointment;
    public function findById(int $id): ?Appointment;
    public function getPatientAppointments(int $patientId, ?string $status = null): LengthAwarePaginator;
    public function getUpcomingForPatient(int $patientId): Collection;
    public function updateStatus(Appointment $appointment, string $status, ?string $reason = null): Appointment;
    public function cancelAppointment(Appointment $appointment, string $reason, int $cancelledBy): Appointment;
    public function getAvailableSlots(int $doctorId, int $clinicId, string $date): array;
}