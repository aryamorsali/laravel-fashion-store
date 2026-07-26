<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Market\Product;
use function Symfony\Component\Clock\now;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        return [
            'name' => $this->faker->words(3, true),
            'has_color' => 1,
            'has_size' => 1,
            'image' => fake()->imageUrl(600, 600, 'fashion', true),
            'slug' => $this->faker->slug(),
            'base_price' => $this->faker->numberBetween(10, 200),
            'description' => $this->faker->paragraph(),
            'brand_id' => 1, // یا مقدار تستی دلخواه
            'category_id' => 12,
            'tags' => implode(',', fake()->randomElements(['fashion','modern','sport','luxury'], 2)),
            'status' => 'published',
            'published_at' => now(),
        ];
    }
}
