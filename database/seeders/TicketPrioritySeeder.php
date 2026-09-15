<?php

namespace Database\Seeders;

use App\Models\Ticket\TicketPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ticketPriorities = [
            [
                'id'     => 1,
                'name'   => 'Low', 
                'status' => 1,
            ],
            [
                'id'     => 2,
                'name'   => 'Medium', 
                'status' => 1,
            ],
            [
                'id'     => 3,
                'name'   => 'High', 
                'status' => 1,
            ],
            [
                'id'     => 4,
                'name'   => 'Critical', 
                'status' => 1,
            ],
        ];

        foreach ($ticketPriorities as $priority) {
            TicketPriority::updateOrCreate(
                ['id' => $priority['id']],
                $priority
            );
        }
    }
}
