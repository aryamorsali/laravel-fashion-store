<?php

namespace Database\Seeders;

use App\Models\Market\ProductSize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            // Clothing Sizes
            [
                'id'   => 1,
                'name' => 'XS',
                'type' => 'clothing',
            ],
            [
                'id'   => 2,
                'name' => 'S',
                'type' => 'clothing',
            ],
            [
                'id'   => 3,
                'name' => 'M',
                'type' => 'clothing',
            ],
            [
                'id'   => 4,
                'name' => 'L',
                'type' => 'clothing',
            ],
            [
                'id'   => 5,
                'name' => 'XL',
                'type' => 'clothing',
            ],
            [
                'id'   => 6,
                'name' => '2XL',
                'type' => 'clothing',
            ],

            // Footwear Sizes
            [
                'id'   => 7,
                'name' => '39',
                'type' => 'footwear',
            ],
            [
                'id'   => 8,
                'name' => '40',
                'type' => 'footwear',
            ],
            [
                'id'   => 9,
                'name' => '41',
                'type' => 'footwear',
            ],
            [
                'id'   => 10,
                'name' => '42',
                'type' => 'footwear',
            ],
        ];

        foreach ($sizes as $size) {
            ProductSize::updateOrCreate(
                ['id' => $size['id']],
                $size
            );
        }
    }
}
