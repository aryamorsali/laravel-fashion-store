<?php

namespace Database\Factories\Market;

use App\Models\Market\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Market\Product;
use App\Models\Market\ProductCategory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        $images = [
            'images/product/seed/product-01.jpg',
            'images/product/seed/product-02.jpg',
            'images/product/seed/product-03.jpg',
            'images/product/seed/product-04.jpg',
            'images/product/seed/product-05.jpg',
            'images/product/seed/product-06.jpg',
            'images/product/seed/product-07.jpg',
            'images/product/seed/product-08.jpg',
            'images/product/seed/product-09.jpg',
            'images/product/seed/product-10.jpg',
            'images/product/seed/product-11.jpg',
            'images/product/seed/product-12.jpg',
            'images/product/seed/product-13.jpg',
            'images/product/seed/product-14.jpg',
            'images/product/seed/product-15.jpg',
            'images/product/seed/product-16.jpg',

        ];

        $imageName = fake()->randomElement($images);

        return [
            'name'         => ucfirst($name),
            'has_color'    => fake()->boolean(80),
            'has_size'     => fake()->boolean(80),
            'image' => [
                'indexArray' => [
                    'large' => $imageName,
                    'main'  => $imageName,
                    'small' => $imageName,
                ],
                'directory'    => 'images/product/seed',
                'currentImage' => 'main',
            ],
            'base_price'   => fake()->numberBetween(20, 300),
            'description'  => fake()->paragraph(3),
            'brand_id'     => Brand::inRandomOrder()->value('id'),
            'category_id'  => ProductCategory::inRandomOrder()->value('id'),
            'status'       => 'published',
            'published_at' => now()->subDays(rand(1,20)),
        ];
    }
}
