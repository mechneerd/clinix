<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\DoctorSchedule;
use App\Models\StaffProfile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffService
{
    public function getClinicStaff(int $clinicId, ?string $role = null)
    {
        return StaffProfile::with(['user.roles', 'department'])
            ->where('clinic_id', $clinicId)
            ->when($role, function ($q) use ($role) {
                $q->whereHas('user.roles', fn($r) => $r->where('name', $role));
            })
            ->latest()
            ->paginate(15);
    }

    public function addStaff(Clinic $clinic, array $data): User
    {
        return DB::transaction(function () use ($clinic, $data) {
            // Create or find user by email
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'phone'             => $data['phone'] ?? null,
                    'password'          => Hash::make($data['password'] ?? Str::random(12)),
                    'registration_type' => 'admin',
                    'email_verified_at' => now(),
                ]
            );

            // Assign Spatie role
            $user->syncRoles([$data['role']]);

            // Staff profile
            StaffProfile::updateOrCreate(
                ['user_id' => $user->id, 'clinic_id' => $clinic->id],
                [
                    'department_id'      => $data['department_id'] ?? null,
                    'employee_id'        => $data['employee_id'] ?? 'EMP-' . strtoupper(Str::random(6)),
                    'qualification'      => $data['qualification'] ?? null,
                    'specializations'    => $data['specializations'] ?? null,
                    'experience_years'   => $data['experience_years'] ?? 0,
                    'license_number'     => $data['license_number'] ?? null,
                    'license_expiry'     => $data['license_expiry'] ?? null,
                    'consultation_fee'   => $data['consultation_fee'] ?? 0,
                    'employment_type'    => $data['employment_type'] ?? 'full_time',
                    'joining_date'       => $data['joining_date'] ?? today(),
                    'is_available_for_online' => $data['is_available_for_online'] ?? false,
                    'biography'          => $data['biography'] ?? null,
                ]
            );

            // User profile
            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_type'     => $data['role'],
                    'gender'        => $data['gender'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'specialty'     => $data['specializations'] ?? null,
                ]
            );

            return $user->load(['staffProfile', 'roles']);
        });
    }

    public function saveDoctorSchedule(int $doctorId, int $clinicId, array $schedules): void
    {
        DB::transaction(function () use ($doctorId, $clinicId, $schedules) {
            DoctorSchedule::where('doctor_id', $doctorId)
                ->where('clinic_id', $clinicId)
                ->delete();

            foreach ($schedules as $schedule) {
                if ($schedule['is_available'] ?? false) {
                    DoctorSchedule::create([
                        'doctor_id'     => $doctorId,
                        'clinic_id'     => $clinicId,
                        'day_of_week'   => $schedule['day'],
                        'start_time'    => $schedule['start_time'],
                        'end_time'      => $schedule['end_time'],
                        'slot_duration' => $schedule['slot_duration'] ?? 30,
                        'buffer_time'   => $schedule['buffer_time'] ?? 5,
                        'max_patients'  => $schedule['max_patients'] ?? 10,
                        'is_available'  => true,
                    ]);
                }
            }
        });
    }

    public function removeStaff(StaffProfile $profile): void
    {
        $profile->update(['leaving_date' => today()]);
    }

    public function getStaffStats(int $clinicId): array
    {
        $staff = StaffProfile::where('clinic_id', $clinicId)
            ->whereNull('leaving_date')
            ->with('user.roles')
            ->get();

        return [
            'total'        => $staff->count(),
            'doctors'      => $staff->filter(fn($s) => $s->user?->hasRole('doctor'))->count(),
            'nurses'       => $staff->filter(fn($s) => $s->user?->hasRole('nurse'))->count(),
            'lab_staff'    => $staff->filter(fn($s) => $s->user?->hasRole('lab_technician'))->count(),
            'pharmacy_staff'=> $staff->filter(fn($s) => $s->user?->hasRole('pharmacist'))->count(),
            'others'       => $staff->filter(fn($s) => !$s->user?->hasAnyRole(['doctor','nurse','lab_technician','pharmacist']))->count(),
        ];
    }
}
