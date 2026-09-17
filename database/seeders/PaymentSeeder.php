<?php

namespace Database\Seeders;

use App\Models\Market\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::factory()->count(25)->paid()->create();

        Payment::factory()->count(8)->failed()->create();

        Payment::factory()->count(3)->returned()->create();

        Payment::factory()->count(4)->create();
    }
}
