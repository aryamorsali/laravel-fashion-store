<?php

namespace Database\Seeders;

use App\Models\Market\Product;
use App\Models\Market\ProductColor;
use App\Models\Market\ProductSize;
use App\Models\Market\ProductVariant;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $colorIds = ProductColor::pluck('id');
        $sizeIds  = ProductSize::pluck('id');

        foreach ($products as $product) {
            $hasColor = $product->has_color;
            $hasSize  = $product->has_size;

            for ($i = 0; $i < 5; $i++) {

                $colorId = $hasColor ? $colorIds->random() : null;
                $sizeId  = $hasSize  ? $sizeIds->random()  : null;

                ProductVariant::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'color_id'   => $colorId,
                        'size_id'    => $sizeId,
                    ],
                    [
                        'product_id' => $product->id,
                        'color_id'   => $colorId,
                        'size_id'    => $sizeId,
                        'price'      => $product->base_price + rand(0, 20),
                    ]
                );
            }
        }
        // میکرد بخاطر همین باید دوباره پابلیش کنیم draft بخاطر هوک ک تعریف کردیم در مدل پروداکت محصولاتی ک خاصیت متغیر دارند رو  
        Product::whereHas('variants')->update(['status' => 'published']);
    }
}
