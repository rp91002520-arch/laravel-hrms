<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create([

            'employee_code' => 'EMP001',

            'name' => 'Pankaj Singh',

            'email' => 'pankaj@gmail.com',

            'phone' => '9999999999',

            'department' => 'IT',

            'joining_date' => now()
        ]);
    }
}
