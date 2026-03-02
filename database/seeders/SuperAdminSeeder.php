<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Create Roles ──────────────────────────────────────────────────

        $roles = [
            'super_admin',
            'admin',
            'doctor',
            'nurse',
            'lab_technician',
            'pharmacist',
            'manager',
            'receptionist',
            'patient',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->command->info('✅ Roles created.');

        // ─── 2. Create Super Admin User ───────────────────────────────────────

        $user = User::updateOrCreate(
            ['email' => 'superadmin@clinix.com'],
            [
                'name'              => 'Super Admin',
                'email'             => 'superadmin@clinix.com',
                'phone'             => '+1234567890',
                'password'          => Hash::make('Admin@1234'),
                'registration_type' => 'admin',
                'email_verified'    => true,
                'email_verified_at' => now(),
            ]
        );

        // ─── 3. Assign Role ───────────────────────────────────────────────────

        $user->syncRoles(['super_admin']);

        // ─── 4. Create Profile ────────────────────────────────────────────────

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_type'           => 'super_admin',
                'gender'              => 'prefer_not_to_say',
                'date_of_birth'       => '1990-01-01',
                'license_number'      => 'SUPER-ADMIN-001',
                'specialty'           => 'System Administrator',
                'years_of_experience' => 10,
            ]
        );

        $this->command->info('✅ Super Admin created.');
        $this->command->newLine();
        $this->command->line('  <fg=cyan>Email</>    : superadmin@clinix.com');
        $this->command->line('  <fg=cyan>Password</> : Admin@1234');
        $this->command->newLine();
        $this->command->warn('  ⚠️  Change the password after first login!');
    }
}