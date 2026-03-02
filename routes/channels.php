<?php

use Illuminate\Support\Facades\Broadcast;

// Patient private channel — only the patient themselves can listen
Broadcast::channel('patient.{patientId}', function ($user, $patientId) {
    return (int) $user->id === (int) $patientId;
});

// Clinic channel — admins and staff of that clinic
Broadcast::channel('clinic.{clinicId}', function ($user, $clinicId) {
    // Super admin can listen to everything
    if ($user->hasRole('super_admin')) return true;

    // Clinic owner
    if ($user->clinics()->where('id', $clinicId)->exists()) return true;

    // Staff assigned to clinic
    return $user->staffProfile?->clinic_id == $clinicId;
});