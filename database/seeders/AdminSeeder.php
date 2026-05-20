<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([

            'name' => 'Admin',

            'email' => 'admin@gmail.com',

            'password' => bcrypt('12345678'),

            'role' => 'admin'
        ]);
    }
}