<?php

namespace Database\Factories\Market;

use App\Models\Market\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Market\Product;
use App\Models\Market\ProductCategory;

use function Symfony\Component\Clock\now;

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

        $sampleImages = [
            'images/product-01.jpg',
            'images/product-02.jpg',
            'images/product-03.jpg',
            'images/product-04.jpg',
            'images/product-05.jpg',
            'images/product-06.jpg',
            'images/product-07.jpg',
            'images/product-08.jpg',
            'images/product-09.jpg',
            'images/product-10.jpg',
            'images/product-11.jpg',
            'images/product-12.jpg',
            'images/product-13.jpg',
            'images/product-14.jpg',
            'images/product-15.jpg',
            'images/product-16.jpg',

        ];

        return [
            'name'         => ucfirst($name),
            'has_color'    => fake()->boolean(80),
            'has_size'     => fake()->boolean(80), 
            'image'        => fake()->randomElement($sampleImages),
            'base_price'   => fake()->numberBetween(20, 300),
            'description'  => fake()->paragraph(3),
            'brand_id'     => Brand::inRandomOrder()->value('id'),
            'category_id'  => ProductCategory::inRandomOrder()->value('id'),
            'status'       => 'published',
            'published_at' => now(),
        ];
    }
}
