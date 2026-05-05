<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the agent we created in UserSeeder
        $agent = User::where('email', 'agent@test.com')->first();

        // If the agent doesn't exist, stop to avoid errors
        if (!$agent) {
            return;
        }

        Ticket::create([
            'customer_name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '0771234567',
            'subject' => 'Internet Connection Issue',
            'content' => 'My router is blinking red since this morning.',
            'status' => 'Open',
            'user_id' => $agent->id,
        ]);

        Ticket::create([
            'customer_name' => 'Jane Smith',
            'email' => 'jane@test.lk',
            'phone' => '0719876543',
            'subject' => 'Billing Inquiry',
            'content' => 'I was charged twice for my April subscription.',
            'status' => 'Pending',
            'user_id' => $agent->id,
        ]);
    }
}
