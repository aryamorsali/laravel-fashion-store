<?php

namespace Database\Seeders;

use App\Models\Market\AmazingSale;
use App\Models\Market\Order;
use App\Models\Market\OrderItem;
use App\Models\Market\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = ProductVariant::with('product')->get();
        $amazingSales = AmazingSale::all();

        Order::factory()->count(70)->create()->each(function (Order $order) use ($variants, $amazingSales) {


            for ($i = 0; $i < rand(1, 3); $i++) {

                $variant = $variants->random();
                $product = $variant->product;

                $hasAmazingSale = $amazingSales->isNotEmpty() && rand(0, 1);
                $amazingSale    = $hasAmazingSale ? $amazingSales->random() : null;

                $quantity = rand(1, 5);
                $finalProductPrice  = $variant->price ?? rand(50, 300);
                $finalTotalPrice  = $quantity * $finalProductPrice;

                OrderItem::create([
                    'order_id'       => $order->id,
                    'product_variant_id'   => $variant->id,
                    'amazing_sale_id'   => $amazingSale?->id,
                    'product_snapshot' => json_encode([
                        'name'   =>  $product->name,
                        'base_price' => $product->base_price,
                    ]),
                    'amazing_sale_snapshot'        => $amazingSale ? json_encode([
                        'percentage' => $amazingSale->percentage,
                        'start_date' => $amazingSale->start_date,
                        'end_date'   => $amazingSale->end_date,
                    ]) : null,
                    'amazing_sale_discount_amount' => $amazingSale ? rand(10, 40) : 0,
                    'quantity'                     => $quantity,
                    'final_product_price'          => $finalProductPrice,
                    'final_total_price'            => $finalTotalPrice,
                ]);
            }
        });
    }
}
