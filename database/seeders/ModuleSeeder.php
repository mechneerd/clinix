<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Departments', 'slug' => 'departments', 'icon' => 'folder', 'is_core' => true],
            ['name' => 'Staff Management', 'slug' => 'staff', 'icon' => 'users', 'is_core' => true],
            ['name' => 'Patient Management', 'slug' => 'patients', 'icon' => 'patient', 'is_core' => true],
            ['name' => 'Appointments', 'slug' => 'appointments', 'icon' => 'calendar', 'is_core' => false],
            ['name' => 'Medicine Inventory', 'slug' => 'medicines', 'icon' => 'pill', 'is_core' => false],
            ['name' => 'Laboratory', 'slug' => 'laboratory', 'icon' => 'flask', 'is_core' => false],
            ['name' => 'Billing & Invoices', 'slug' => 'billing', 'icon' => 'dollar', 'is_core' => false],
            ['name' => 'Reports', 'slug' => 'reports', 'icon' => 'chart', 'is_core' => false],
            ['name' => 'Pharmacy & Supply Chain', 'slug' => 'pharmacy', 'icon' => 'pill', 'is_core' => false],
            ['name' => 'Inpatient & Nursing', 'slug' => 'inpatient', 'icon' => 'building', 'is_core' => false],
            ['name' => 'Enterprise Workforce & HR', 'slug' => 'hr', 'icon' => 'users', 'is_core' => false],
            ['name' => 'Global Ledger & Financials', 'slug' => 'finance', 'icon' => 'chart', 'is_core' => false],
            ['name' => 'Compliance & Clinical Safety', 'slug' => 'compliance', 'icon' => 'shield', 'is_core' => false],
        ];

        foreach ($modules as $module) {
            \App\Models\Module::updateOrCreate(['slug' => $module['slug']], $module);
        }
    }
}
