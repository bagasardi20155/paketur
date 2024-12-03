<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'Paketur Jakarta',
            'email' => 'jakarta@paketur.com',
            'phone' => '021-999-28',
        ]);
        Company::create([
            'name' => 'Paketur Jogja',
            'email' => 'jogja@paketur.com',
            'phone' => '021-999-22',
        ]);
    }
}
