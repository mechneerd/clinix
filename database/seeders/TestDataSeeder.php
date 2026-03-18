<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Package;
use App\Models\Staff;
use App\Models\Patient;
use App\Models\Appointment;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $package = Package::where('is_approved', true)->first();
        if (!$package) return;

        // 1. Create a Clinic Admin
        $clinicAdmin = User::updateOrCreate(
            ['email' => 'owner@clinix.com'],
            [
                'name' => 'Clinic Owner',
                'password' => bcrypt('password123'),
                'user_type' => 'clinic_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $clinicAdmin->assignRole('clinic-admin');

        // 2. Create the Clinic
        $clinic = Clinic::updateOrCreate(
            ['slug' => 'city-health-center'],
            [
                'user_id' => $clinicAdmin->id,
                'package_id' => $package->id,
                'name' => 'City Health Center',
                'email' => 'contact@cityhealth.com',
                'phone' => '555-0101',
                'address' => '123 Medical Ave',
                'city' => 'Metropolis',
                'state' => 'Central',
                'country' => 'HealthLand',
                'status' => 'active',
                'package_expires_at' => now()->addYear(),
            ]
        );

        // 3. Departments & Specialties (Created early for staff assignment)
        $genMed = \App\Models\Department::updateOrCreate(
            ['clinic_id' => $clinic->id, 'name' => 'General Medicine'],
            ['code' => 'GM']
        );

        \App\Models\Specialty::firstOrCreate(
            ['clinic_id' => $clinic->id, 'name' => 'Internal Medicine']
        );

        // 4. Create Doctors
        $doctorUser = User::updateOrCreate(
            ['email' => 'doctor@clinix.com'],
            [
                'name' => 'Dr. John Smith',
                'password' => bcrypt('password123'),
                'user_type' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $doctorUser->assignRole('doctor');

        $doctorStaff = Staff::updateOrCreate(
            ['user_id' => $doctorUser->id, 'clinic_id' => $clinic->id],
            [
                'role' => 'doctor',
                'department_id' => $genMed->id,
                'employee_id' => 'DOC001',
                'qualification' => 'MBBS, MD',
                'joining_date' => now()->subYear(),
                'consultation_fee' => 50.00,
                'is_active' => true,
            ]
        );

        // 5. Create Nurses
        $nurseUser = User::updateOrCreate(
            ['email' => 'nurse@clinix.com'],
            [
                'name' => 'Nurse Jane Doe',
                'password' => bcrypt('password123'),
                'user_type' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $nurseUser->assignRole('nurse');

        Staff::updateOrCreate(
            ['user_id' => $nurseUser->id, 'clinic_id' => $clinic->id],
            [
                'role' => 'nurse',
                'department_id' => $genMed->id,
                'employee_id' => 'NUR001',
                'joining_date' => now()->subMonths(6),
                'is_active' => true,
            ]
        );

        // 6. Create Lab Manager
        $labUser = User::updateOrCreate(
            ['email' => 'lab@clinix.com'],
            [
                'name' => 'Lab Manager Mike',
                'password' => bcrypt('password123'),
                'user_type' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $labUser->assignRole('lab_manager');
        Staff::updateOrCreate(
            ['user_id' => $labUser->id, 'clinic_id' => $clinic->id],
            [
                'role' => 'lab_manager',
                'employee_id' => 'LAB001',
                'joining_date' => now()->subMonths(8),
                'is_active' => true,
            ]
        );

        // 7. Create Pharmacy Manager
        $pharmacyUser = User::updateOrCreate(
            ['email' => 'pharmacy@clinix.com'],
            [
                'name' => 'Pharmacist Sarah',
                'password' => bcrypt('password123'),
                'user_type' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $pharmacyUser->assignRole('pharmacy_manager');
        Staff::updateOrCreate(
            ['user_id' => $pharmacyUser->id, 'clinic_id' => $clinic->id],
            [
                'role' => 'pharmacy_manager',
                'employee_id' => 'PHA001',
                'joining_date' => now()->subMonths(4),
                'is_active' => true,
            ]
        );

        // 8. Create Receptionist
        $receptionUser = User::updateOrCreate(
            ['email' => 'reception@clinix.com'],
            [
                'name' => 'Receptionist Amy',
                'password' => bcrypt('password123'),
                'user_type' => 'staff',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $receptionUser->assignRole('receptionist');
        Staff::updateOrCreate(
            ['user_id' => $receptionUser->id, 'clinic_id' => $clinic->id],
            [
                'role' => 'receptionist',
                'employee_id' => 'REC001',
                'joining_date' => now()->subMonths(2),
                'is_active' => true,
            ]
        );

        // 6. Create Patients
        $patientUser = User::updateOrCreate(
            ['email' => 'patient@clinix.com'],
            [
                'name' => 'Alice Patient',
                'password' => bcrypt('password123'),
                'user_type' => 'patient',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $patientUser->assignRole('patient');

        for ($i = 1; $i <= 10; $i++) {
            $patientEmail = $i === 1 ? 'patient@clinix.com' : "patient{$i}@example.com";
            $patient = Patient::updateOrCreate(
                ['email' => $patientEmail],
                [
                    'user_id' => $i === 1 ? $patientUser->id : null,
                    'patient_code' => "PAT00{$i}",
                    'first_name' => $i === 1 ? 'Alice' : 'Patient',
                    'last_name' => $i === 1 ? 'Wonderland' : (string)$i,
                    'phone' => "555-000{$i}",
                    'date_of_birth' => now()->subYears(20 + $i),
                    'gender' => $i % 2 == 0 ? 'male' : 'female',
                    'is_active' => true,
                ]
            );

            if (!$clinic->patients()->where('patient_id', $patient->id)->exists()) {
                $clinic->patients()->attach($patient->id, [
                    'registered_at' => now(),
                    'registration_type' => 'walk_in'
                ]);
            }

            // Create an appointment for each patient
            $appointment = Appointment::updateOrCreate(
                [
                    'clinic_id' => $clinic->id,
                    'patient_id' => $patient->id,
                    'appointment_date' => now()->addDays($i - 5)->toDateString(),
                    'start_time' => Carbon::createFromTime(9 + ($i % 8), 0)->toTimeString(),
                ],
                [
                    'doctor_id' => $doctorStaff->id,
                    'end_time' => Carbon::createFromTime(10 + ($i % 8), 0)->toTimeString(),
                    'status' => $i < 5 ? 'completed' : 'scheduled',
                    'chief_complaint' => 'General checkup',
                    'fee' => 50.00,
                ]
            );

            if ($appointment->status === 'completed') {
                $record = \App\Models\MedicalRecord::updateOrCreate(
                    ['appointment_id' => $appointment->id],
                    [
                        'patient_id' => $patient->id,
                        'doctor_id' => $doctorStaff->id,
                        'diagnosis' => 'Healthy condition',
                        'treatment_plan' => 'Regular exercise and balanced diet.',
                    ]
                );

                $prescription = \App\Models\Prescription::firstOrCreate(
                    ['medical_record_id' => $record->id],
                    [
                        'prescription_no' => 'RX-' . strtoupper(dechex(time()) . $i),
                        'prescribed_date' => now(),
                    ]
                );

                // 6. Invoices & Payments (Only for completed appointments)
                $invoice = \App\Models\Invoice::updateOrCreate(
                    ['invoice_no' => 'INV-' . strtoupper(dechex(time()) . $i)],
                    [
                        'clinic_id' => $clinic->id,
                        'patient_id' => $patient->id,
                        'subtotal' => 50.00,
                        'tax_amount' => 5.00,
                        'total_amount' => 55.00,
                        'paid_amount' => 55.00,
                        'status' => 'paid',
                    ]
                );

                \App\Models\Payment::firstOrCreate(
                    ['invoice_id' => $invoice->id],
                    [
                        'amount' => 55.00,
                        'payment_method' => 'cash',
                    ]
                );
            }
        }

        // 8. Medicines
        \App\Models\Medicine::updateOrCreate(
            ['clinic_id' => $clinic->id, 'name' => 'Paracetamol'],
            [
                'generic_name' => 'Acetaminophen',
                'category' => 'Analgesic',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'price' => 5.00,
                'stock_quantity' => 100,
            ]
        );

        // 9. Lab Tests
        $labTest = \App\Models\LabTest::updateOrCreate(
            ['clinic_id' => $clinic->id, 'code' => 'BS001'],
            [
                'name' => 'Blood Sugar',
                'price' => 20.00,
            ]
        );

        // 10. Lab Orders
        $labOrder = \App\Models\LabOrder::updateOrCreate(
            ['clinic_id' => $clinic->id, 'order_no' => 'LAB-TEST-001'],
            [
                'patient_id' => $patientUser->id,
                'doctor_id' => $doctorStaff->id,
                'status' => 'pending',
                'total_amount' => 20.00,
            ]
        );

        \App\Models\LabOrderItem::firstOrCreate(
            ['lab_order_id' => $labOrder->id, 'lab_test_id' => $labTest->id]
        );
    }
}
