<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all(['id']);

        $user =  User::create([
            'name' => "Elham Putra",
            'email' => "superadmin@paketur.com",
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('superadministrator');

        $manager_jogja = User::create([
            'name' => "Paulus Adhiatma",
            'email' => "managerjogja@paketur.com",
            'password' => bcrypt('password'),
        ]);
        $manager_jakarta = User::create([
            'name' => "Ajrina Wahyudi",
            'email' => "managerjakarta@paketur.com",
            'password' => bcrypt('password'),
        ]);
        $employee_jogja = User::create([
            'name' => "Dicky Pamungkas",
            'email' => "employeejogja@paketur.com",
            'password' => bcrypt('password'),
        ]);
        $employee_jakarta = User::create([
            'name' => "Raihan Agwicahya",
            'email' => "employeejakarta@paketur.com",
            'password' => bcrypt('password'),
        ]);

        Staff::create([
            'user_id' => $manager_jogja->id,
            'company_id' => $companies[1]->id,
            'address' => 'Jogjakarta',
            'hp' => '085155440522',
        ]);
        $manager_jogja->assignRole('manager');
        Staff::create([
            'user_id' => $manager_jakarta->id,
            'company_id' => $companies[0]->id,
            'address' => 'Jakarta',
            'hp' => '085155430521',
        ]);
        $manager_jakarta->assignRole('manager');
        Staff::create([
            'user_id' => $employee_jogja->id,
            'company_id' => $companies[1]->id,
            'address' => 'Jogjakarta',
            'hp' => '085155440522',
        ]);
        $employee_jogja->assignRole('employee');
        Staff::create([
            'user_id' => $employee_jakarta->id,
            'company_id' => $companies[0]->id,
            'address' => 'Jakarta',
            'hp' => '085155430521',
        ]);
        $employee_jakarta->assignRole('employee');
    }
}
