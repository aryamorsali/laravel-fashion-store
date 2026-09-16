<?php

namespace Database\Factories\Content;

use App\Models\Market\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body'             => fake()->realText(200),
            'parent_id'        => null, 
            'author_id'        => User::inRandomOrder()->value('id') ,
            'commentable_type' => Product::class,
            'commentable_id'   => Product::inRandomOrder()->value('id') ,
            'rating'           => fake()->numberBetween(1, 5),
            'seen'             => fake()->randomElement([0, 1]),
            'approved'         => fake()->randomElement([0, 1]),
        ];
    }
}
