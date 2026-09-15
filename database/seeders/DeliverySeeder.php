<?php

namespace Database\Seeders;

use App\Models\Market\Delivery;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deliveries = [
            [
                'id'            => 1,
                'name'          => 'Standard Delivery (DHL / Postal)',
                'delivery_cost' => 5,  
                'delivery_days' => 5, 
                'status'        => 1,  
            ],
            [
                'id'            => 2,
                'name'          => 'Express Courier (FedEx / UPS)',
                'delivery_cost' => 15, 
                'delivery_days' => 2,  
                'status'        => 1,  
            ],
            [
                'id'            => 3,
                'name'          => 'Next Day Air',
                'delivery_cost' => 25, 
                'delivery_days' => 1, 
                'status'        => 1, 
            ],
        ];

        foreach ($deliveries as $delivery) {
            Delivery::updateOrCreate(
                ['id' => $delivery['id']],
                $delivery
            );
        }
    }
}
