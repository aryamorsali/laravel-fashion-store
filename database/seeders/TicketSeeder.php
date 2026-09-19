<?php

namespace Database\Seeders;

use App\Models\Ticket\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        Ticket::factory()->count(30)->create();
    }
}
