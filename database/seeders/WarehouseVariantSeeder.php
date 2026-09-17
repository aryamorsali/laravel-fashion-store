<?php

namespace Database\Seeders;

use App\Models\Market\ProductVariant;
use App\Models\Market\Warehouse;
use App\Models\Market\WarehouseVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WarehouseVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::all();
        $variants   = ProductVariant::all();

        foreach ($warehouses as $warehouse) {
            foreach ($variants as $variant) {
                WarehouseVariant::updateOrCreate(
                    [
                        'warehouse_id'       => $warehouse->id,
                        'product_variant_id' => $variant->id,
                    ],
                    [
                        'stock'    => rand(5, 30),
                        'reserved' => rand(0, 5),
                        'sold'     => rand(0, 20),
                    ]
                );
            }
        }
    }
}
