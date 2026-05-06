<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create the Support Agent (role = 2)
        User::create([
            'name' => 'Support Agent',
            'email' => 'agent@test.com',
            'password' => Hash::make('password123'),
            'role' => 2,
        ]);

        // Create test customer (role = 1)
        User::create([
            'name' => 'Kamal',
            'email' => 'kamal@test.com',
            'password' => Hash::make('password123'),
            'role' => 1,
        ]);

        // Create 30 random customer users (role = 1)
        User::factory(30)->create(['role' => 1]);
    }
}