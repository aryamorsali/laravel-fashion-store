<?php

namespace Database\Seeders;

use App\Models\Market\AmazingSale;
use App\Models\Market\ProductVariant;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmazingSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variantIds = ProductVariant::inRandomOrder()->take(10)->pluck('id');

        foreach ($variantIds as $variantId) {
            AmazingSale::updateOrCreate(
                [
                    'product_variant_id' => $variantId,
                ],
                [
                    'product_variant_id' => $variantId,
                    'percentage'         => rand(10, 50),
                    'start_date'         => Carbon::now()->subDays(10),
                    'end_date'           => Carbon::now()->addMonths(1),
                    'is_active'          => 1,
                ]
            );
        }
    }
}
