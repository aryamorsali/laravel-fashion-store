<?php

namespace Database\Seeders;

use App\Models\Market\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Address::factory()->count(80)->create();
    }
}
