<?php

namespace Database\Seeders;

use App\Models\Market\Gallery;
use App\Models\Market\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            'images/product_gallery/seed/product-01.jpg',
            'images/product_gallery/seed/product-02.jpg',
            'images/product_gallery/seed/product-03.jpg',
            'images/product_gallery/seed/product-04.jpg',
            'images/product_gallery/seed/product-05.jpg',
            'images/product_gallery/seed/product-06.jpg',
            'images/product_gallery/seed/product-07.jpg',
            'images/product_gallery/seed/product-08.jpg',
            'images/product_gallery/seed/product-09.jpg',
            'images/product_gallery/seed/product-10.jpg',
            'images/product_gallery/seed/product-11.jpg',
            'images/product_gallery/seed/product-12.jpg',
            'images/product_gallery/seed/product-13.jpg',
            'images/product_gallery/seed/product-14.jpg',
            'images/product_gallery/seed/product-15.jpg',
            'images/product_gallery/seed/product-16.jpg',
            'images/product_gallery/seed/product-detail-01.jpg',
            'images/product_gallery/seed/product-detail-02.jpg',
            'images/product_gallery/seed/product-detail-03.jpg',
        ];

        Product::each(function (Product $product) use ($images) {
            $imageName = fake()->randomElement($images);

            Gallery::updateOrCreate(
                [
                    'product_id' => $product->id,
                ],
                [
                    'image' => [
                        'indexArray' => [
                            'large' => $imageName,
                            'main'  => $imageName,
                            'small' => $imageName,
                        ],
                        'directory'    => 'images/product_gallery/seed',
                        'currentImage' => 'main',
                    ],
                ]
            );
        });
    }
}
