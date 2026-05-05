<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Call your specific seeders in order (Users first, then Tickets)
        $this->call([
            UserSeeder::class,
            TicketSeeder::class,
        ]);

        // 2. Optional: Create a generic test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}