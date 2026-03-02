<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use App\Models\DoctorSchedule;
use App\Repositories\Interfaces\AppointmentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function createAppointment(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            $data['appointment_number'] = Appointment::generateNumber();

            $appointment = Appointment::create($data);

            AppointmentStatusHistory::create([
                'appointment_id' => $appointment->id,
                'from_status'    => '',
                'to_status'      => $appointment->status,
                'changed_by'     => $data['booked_by'] ?? $data['patient_id'],
                'changed_at'     => now(),
                'remarks'        => 'Appointment created',
            ]);

            return $appointment->load(['doctor', 'clinic', 'patient', 'department']);
        });
    }

    public function findById(int $id): ?Appointment
    {
        return Appointment::with(['doctor', 'clinic', 'patient', 'department', 'visit'])->find($id);
    }

    public function getPatientAppointments(int $patientId, ?string $status = null): LengthAwarePaginator
    {
        return Appointment::with(['doctor', 'clinic', 'department'])
            ->where('patient_id', $patientId)
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->paginate(10);
    }

    public function getUpcomingForPatient(int $patientId): Collection
    {
        return Appointment::with(['doctor.staffProfile', 'clinic', 'department'])
            ->where('patient_id', $patientId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();
    }

    public function updateStatus(Appointment $appointment, string $status, ?string $reason = null): Appointment
    {
        $old = $appointment->status;

        $appointment->update([
            'status' => $status,
            'cancellation_reason' => $reason,
        ]);

        AppointmentStatusHistory::create([
            'appointment_id' => $appointment->id,
            'from_status'    => $old,
            'to_status'      => $status,
            'changed_by'     => auth()->id(),
            'changed_at'     => now(),
            'remarks'        => $reason,
        ]);

        return $appointment->fresh(['doctor', 'clinic', 'patient']);
    }

    public function cancelAppointment(Appointment $appointment, string $reason, int $cancelledBy): Appointment
    {
        $old = $appointment->status;
        $appointment->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_by'        => $cancelledBy,
            'cancelled_at'        => now(),
        ]);

        AppointmentStatusHistory::create([
            'appointment_id' => $appointment->id,
            'from_status'    => $old,
            'to_status'      => 'cancelled',
            'changed_by'     => $cancelledBy,
            'changed_at'     => now(),
            'remarks'        => $reason,
        ]);

        return $appointment->fresh(['doctor', 'clinic', 'patient']);
    }

    public function getAvailableSlots(int $doctorId, int $clinicId, string $date): array
    {
        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));

        $schedule = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$schedule) return [];

        $bookedTimes = Appointment::where('doctor_id', $doctorId)
            ->where('clinic_id', $clinicId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->pluck('start_time')
            ->toArray();

        $slots = [];
        $current = Carbon::parse($date . ' ' . $schedule->start_time);
        $end     = Carbon::parse($date . ' ' . $schedule->end_time);
        $step    = $schedule->slot_duration + $schedule->buffer_time;

        while ($current->copy()->addMinutes($schedule->slot_duration)->lte($end)) {
            $timeStr = $current->format('H:i:s');
            $slots[] = [
                'time'      => $timeStr,
                'label'     => $current->format('h:i A'),
                'available' => !in_array($timeStr, $bookedTimes),
            ];
            $current->addMinutes($step);
        }

        return $slots;
    }
}