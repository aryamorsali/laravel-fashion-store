<?php

namespace Database\Factories\Market;

use App\Models\Market\Address;
use App\Models\Market\Delivery;
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
        $delivery = Delivery::inRandomOrder()->first();

        $order_final_amount = fake()->numberBetween(100, 1000);
        $order_discount_amount = fake()->numberBetween(10, 60);
        $order_coupon_discount_amount = fake()->numberBetween(5, 30);
        $order_common_discount_amount = fake()->numberBetween(5, 30);
        $order_total_products_discount_amount = $order_discount_amount + $order_coupon_discount_amount + $order_common_discount_amount;


        $isPaid = fake()->boolean(60);

        if ($isPaid) {
            $paymentStatus = 'paid';
            $orderStatus = fake()->randomElement(['confirmed', 'confirmed', 'not_checked', 'awaiting_confirmation']);
            $deliveryStatus = fake()->randomElement(['sending', 'sending', 'shipped', 'delivered']);
        } else {
            $paymentStatus = fake()->randomElement(['unpaid', 'failed']);
            $orderStatus = fake()->randomElement(['canceled', 'not_confirmed']);
            $deliveryStatus = 'canceled';
        }

        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'address_id' => Address::inRandomOrder()->value('id'),
            'delivery_id' => $delivery->id,
            'delivery_amount' => $delivery->delivery_cost,
            'delivery_status' => $deliveryStatus,
            'delivery_date' => $deliveryStatus == 'canceled' ? null :  $randomDate->addDays($delivery->delivery_days),
            'payment_status' => $paymentStatus,
            'order_final_amount' => $order_final_amount,
            'order_total_products_discount_amount' => $order_total_products_discount_amount,
            'order_discount_amount' => $order_discount_amount,
            'order_coupon_discount_amount' => $order_coupon_discount_amount,
            'order_common_discount_amount' => $order_common_discount_amount,
            'order_status' => $orderStatus,
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }
}
