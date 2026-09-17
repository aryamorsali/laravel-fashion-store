<?php

namespace Database\Factories\Market;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // بین 6 ماه گذشته
        $randomDate = Carbon::now()->subDays(rand(0, 180));

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'order_final_amount' => $this->faker->numberBetween(100, 5000),
            'order_total_products_discount_amount' => $this->faker->numberBetween(10, 90),
            'order_discount_amount' => $this->faker->numberBetween(10, 60),
            'order_coupon_discount_amount' => $this->faker->numberBetween(10, 30),
            'order_common_discount_amount' => $this->faker->numberBetween(10, 30),
            'order_status' => 'confirmed',
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }
}
