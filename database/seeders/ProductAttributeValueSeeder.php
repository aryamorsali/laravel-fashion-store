<?php

namespace Database\Seeders;

use App\Models\Market\Product;
use App\Models\Market\ProductAttribute;
use App\Models\Market\ProductAttributeValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::inRandomOrder()->take(70)->get();

        $attrs = ProductAttribute::where('is_global', true)->get()->keyBy('name'); // Material, Weight, Color, Size, Length, Width, Height

        foreach ($products as $product) {
            $rows = [
                [
                    'product_attribute_id' => $attrs['Material']->id ?? null,
                    'value' => fake()->randomElement(['Cotton', 'Leather', 'Polyester', 'Denim']),
                ],
                [
                    'product_attribute_id' => $attrs['Color']->id ?? null,
                    'value' => fake()->safeColorName(),
                ],
                [
                    'product_attribute_id' => $attrs['Size']->id ?? null,
                    'value' => fake()->randomElement(['S', 'M', 'L', 'XL']),
                ],
                [
                    'product_attribute_id' => $attrs['Weight']->id ?? null,
                    'value' =>  fake()->randomFloat(2, 0.1, 5.0), // kg 
                ],
                [
                    'product_attribute_id' => $attrs['Length']->id ?? null,
                    'value' => fake()->numberBetween(10, 200), // cm
                ],
            ];

            foreach ($rows as $row) {
                if (!$row['product_attribute_id']) continue;

                ProductAttributeValue::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'product_attribute_id' => $row['product_attribute_id'],
                    ],
                    [
                        'value' => $row['value'],
                    ]
                );
            }
        }
    }
}
