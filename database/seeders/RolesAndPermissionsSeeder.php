<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/RolesAndPermissionsSeeder.php
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (['admin', 'patient', 'doctor', 'nurse', 'lab_technician', 'pharmacist', 'manager', 'receptionist', 'super_admin'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
