<?php

namespace Database\Factories\Market;

use App\Models\Market\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status    = fake()->randomElement(['paid', 'paid', 'failed', 'returned', 'unpaid']);
        $amount = fake()->numberBetween(50, 1000);
        $authority = 'A' . Str::random(30);
        switch ($status) {
            case 'unpaid':
                $second_response = json_encode(['status' => 'NOK', 'authority' => $authority]);
                break;

            case 'failed':
                $second_response =   null;
                break;

            default:
                $second_response =    json_encode([
                    'reference_id' =>  fake()->numberBetween(100000000, 999999999),
                    'driver'       => 'zarinpal',
                    'amount'       => $amount,
                ]);
                break;
        }
        
        return [
            'user_id'         => User::inRandomOrder()->value('id'),
            'order_id'        => Order::inRandomOrder()->value('id'),
            'amount'          => $amount,
            'gateway'         => 'zarinpal',
            'transaction_id'  => $authority,
            'status'          => $status,
            'first_response' => json_encode([
                'transaction_id' => $authority,
                'gateway'        => 'zarinpal',
                'driver_class'   => 'Shetabit\\Multipay\\Drivers\\Zarinpal\\Zarinpal',
                'amount'         => $amount,
                'currency'       => 'T',
            ]),
            'second_response' => $second_response,
            'paid_at'         => now()->subDays(fake()->numberBetween(1, 180)),
        ];
    }
}
