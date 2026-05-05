<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create the Support Agent
        User::create([
            'name' => 'Support Agent',
            'email' => 'agent@test.com',
            'password' => Hash::make('password123'),
        ]);

        // Create 10 random users for testing
        User::factory(30)->create();
    }
}