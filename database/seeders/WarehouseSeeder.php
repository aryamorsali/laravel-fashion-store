<?php

namespace Database\Seeders;

use App\Models\Market\Warehouse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'id'      => 1,
                'name'    => 'Central Warehouse (Tehran)',
                'address' => 'Tehran, Shodosh St, No. 45',
            ],
            [
                'id'      => 2,
                'name'    => 'West Region Fulfillment Center',
                'address' => 'Karaj, Special Road, Km 14',
            ],
            [
                'id'      => 3,
                'name'    => 'Return & Repair Warehouse',
                'address' => 'Tehran, Azadi Sq, Building 12',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::updateOrCreate(
                ['id' => $warehouse['id']],
                $warehouse
            );
        }
    }
}
