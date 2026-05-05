<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category; //  ADD THIS

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agent = User::where('email', 'agent@test.com')->first();

        if (!$agent) return;

        // GET EXISTING CATEGORY
        $category = Category::first();

        //  STOP if no category exists
        if (!$category) {
            echo "No category found. Please create one first.\n";
            return;
        }

        $tickets = [
            [
                'customer_name' => 'Michael Perera',
                'email' => 'michael@test.lk',
                'phone' => '0771111111',
                'description' => 'Internet connection is very slow at night.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Nimal Silva',
                'email' => 'nimal@test.lk',
                'phone' => '0772222222',
                'description' => 'Cannot login to my account.',
                'status' => 1,
            ],
            [
                'customer_name' => 'Kasun Jayasinghe',
                'email' => 'kasun@test.lk',
                'phone' => '0773333333',
                'description' => 'Forgot my password and reset email not working.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Saman Kumara',
                'email' => 'saman@test.lk',
                'phone' => '0774444444',
                'description' => 'Router keeps restarting automatically.',
                'status' => 2,
            ],
            [
                'customer_name' => 'Dilani Fernando',
                'email' => 'dilani@test.lk',
                'phone' => '0775555555',
                'description' => 'Billing issue for last month.',
                'status' => 1,
            ],
            [
                'customer_name' => 'Ravi De Silva',
                'email' => 'ravi@test.lk',
                'phone' => '0776666666',
                'description' => 'Need help upgrading my plan.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Anjali Perera',
                'email' => 'anjali@test.lk',
                'phone' => '0777777777',
                'description' => 'App crashes on startup.',
                'status' => 2,
            ],
            [
                'customer_name' => 'Tharindu Lakshan',
                'email' => 'tharindu@test.lk',
                'phone' => '0778888888',
                'description' => 'Payment gateway not working.',
                'status' => 1,
            ],
            [
                'customer_name' => 'Sanduni Wickramasinghe',
                'email' => 'sanduni@test.lk',
                'phone' => '0779999999',
                'description' => 'Email notifications not received.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Chaminda Gunasekara',
                'email' => 'chaminda@test.lk',
                'phone' => '0711111111',
                'description' => 'Unable to upload files.',
                'status' => 2,
            ],
            [
                'customer_name' => 'Heshan Rodrigo',
                'email' => 'heshan@test.lk',
                'phone' => '0712222222',
                'description' => 'System shows error 500.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Ishara Madushani',
                'email' => 'ishara@test.lk',
                'phone' => '0713333333',
                'description' => 'Account suspended without reason.',
                'status' => 1,
            ],
            [
                'customer_name' => 'Supun Chathuranga',
                'email' => 'supun@test.lk',
                'phone' => '0714444444',
                'description' => 'Cannot access dashboard.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Piumi Hansika',
                'email' => 'piumi@test.lk',
                'phone' => '0715555555',
                'description' => 'Profile update not saving.',
                'status' => 2,
            ],
            [
                'customer_name' => 'Gayan Weerasinghe',
                'email' => 'gayan@test.lk',
                'phone' => '0716666666',
                'description' => 'API response is too slow.',
                'status' => 1,
            ],
            [
                'customer_name' => 'Nadeesha Fernando',
                'email' => 'nadeesha@test.lk',
                'phone' => '0717777777',
                'description' => 'Search function not working.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Kavindu Senanayake',
                'email' => 'kavindu@test.lk',
                'phone' => '0718888888',
                'description' => 'Website not loading on mobile.',
                'status' => 2,
            ],
            [
                'customer_name' => 'Hasini Perera',
                'email' => 'hasini@test.lk',
                'phone' => '0719999999',
                'description' => 'Subscription not activated.',
                'status' => 1,
            ],
            [
                'customer_name' => 'Roshan Jayawardena',
                'email' => 'roshan@test.lk',
                'phone' => '0701111111',
                'description' => 'Error when downloading reports.',
                'status' => 0,
            ],
            [
                'customer_name' => 'Thilini Abeysekara',
                'email' => 'thilini@test.lk',
                'phone' => '0702222222',
                'description' => 'Live chat not responding.',
                'status' => 2,
            ],
        ];

        foreach ($tickets as $ticket) {
            Ticket::create([
                'category_id'   => $category->id, 
                'customer_name' => $ticket['customer_name'],
                'email'         => $ticket['email'],
                'phone'         => $ticket['phone'],
                'description'   => $ticket['description'],
                'ref'           => 'TKT-' . strtoupper(bin2hex(random_bytes(4))),
                'status'        => $ticket['status'],
            ]);
        }
    }
}