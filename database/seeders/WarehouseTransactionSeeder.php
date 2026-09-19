<?php

namespace Database\Seeders;

use App\Models\Market\WarehouseTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       WarehouseTransaction::factory()->count(30)->create();
    }
}
