<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create all roles
        $roles = [
            'super-admin',
            'clinic-admin',
            'doctor',
            'nurse',
            'lab_manager',
            'pharmacy_manager',
            'reception_manager',
            'lab_worker',
            'pharmacy_worker',
            'receptionist',
            'patient'
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Create Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@clinix.com'],
            [
                'name' => 'Super Administrator',
                'password' => bcrypt('password123'),
                'phone' => '+1234567890',
                'user_type' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Create default packages
        Package::create([
            'name' => 'Starter',
            'description' => 'Perfect for small clinics',
            'price' => 49.99,
            'billing_cycle' => 'monthly',
            'duration_days' => 30,
            'is_active' => true,
            'is_approved' => true,
            'max_clinics' => 1,
            'max_labs' => 0,
            'max_doctors' => 2,
            'max_staff' => 5,
            'max_patients_per_month' => 100,
            'storage_limit_mb' => 1024,
            'api_access' => false,
            'white_label' => false,
            'advanced_reporting' => false,
            'sms_notifications' => false,
            'telemedicine' => false,
        ]);

        Package::create([
            'name' => 'Professional',
            'description' => 'For growing practices',
            'price' => 149.99,
            'billing_cycle' => 'monthly',
            'duration_days' => 30,
            'is_active' => true,
            'is_approved' => true,
            'max_clinics' => 3,
            'max_labs' => 1,
            'max_doctors' => 10,
            'max_staff' => 25,
            'max_patients_per_month' => 1000,
            'storage_limit_mb' => 10240,
            'api_access' => true,
            'white_label' => false,
            'advanced_reporting' => true,
            'sms_notifications' => true,
            'telemedicine' => true,
        ]);

        Package::create([
            'name' => 'Enterprise',
            'description' => 'For large healthcare organizations',
            'price' => 499.99,
            'billing_cycle' => 'monthly',
            'duration_days' => 30,
            'is_active' => true,
            'is_approved' => true,
            'max_clinics' => 10,
            'max_labs' => 5,
            'max_doctors' => 50,
            'max_staff' => 200,
            'max_patients_per_month' => null,
            'storage_limit_mb' => 102400,
            'api_access' => true,
            'white_label' => true,
            'advanced_reporting' => true,
            'sms_notifications' => true,
            'telemedicine' => true,
        ]);

        $this->call([
            CountrySeeder::class,
            LocationSeeder::class,
            TestDataSeeder::class,
        ]);
    }
}