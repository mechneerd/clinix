<?php

use App\Models\Patient;
use App\Models\PatientAllergy;

$patients = Patient::whereNotNull('allergies')->get();

foreach ($patients as $patient) {
    if (is_array($patient->allergies)) {
        foreach ($patient->allergies as $allergen) {
            PatientAllergy::updateOrCreate([
                'patient_id' => $patient->id,
                'allergen' => $allergen,
            ]);
        }
    }
}

echo "Successfully migrated allergies for " . $patients->count() . " patients.\n";
