<?php

namespace Database\Factories\Content;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = fake()->boolean(60) ? User::inRandomOrder()->first() : null;

        return [
            'user_id'    => $user?->id,
            'email'      => $user ? $user->email : fake()->safeEmail(),
            'body'       => fake()->realText(150),
        ];
    }
}
