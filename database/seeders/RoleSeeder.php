<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = Role::create(['name' => 'superadministrator']);
        $manager = Role::create(['name' => 'manager']);
        $employee = Role::create(['name' => 'employee']);

        $superadmin->givePermissionTo(['superadministrator']);
        $manager->givePermissionTo(['manager']);
        $employee->givePermissionTo(['employee']);
    }
}
