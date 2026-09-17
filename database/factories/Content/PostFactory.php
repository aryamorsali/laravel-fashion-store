<?php

namespace Database\Factories\Content;

use App\Models\Content\PostCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title'       => fake()->unique()->sentence(rand(4, 7)),
            'summary'     => fake()->paragraph(2),
            'body'        => fake()->paragraphs(rand(3, 6), true),
            'image'       => 'images/post/blog-0' . rand(1, 5) . '.jpg',
            'author_id'   => User::inRandomOrder()->value('id'),
            'category_id' => PostCategory::inRandomOrder()->value('id') ,
            'status'      => 1,
            'published_at' => now()->subDays(rand(1, 30)),
        ];
    }
}
