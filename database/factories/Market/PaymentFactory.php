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
        return [
            'user_id'         => User::inRandomOrder()->value('id'),
            'order_id'        => Order::inRandomOrder()->value('id'),
            'amount'          => fake()->random(50, 400),
            'gateway'         => 'zarinpal',
            'transaction_id'  => null,
            'status'          => 'unpaid',
            'first_response'  => null,
            'second_response' => null,
            'paid_at'         => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            $amount = $attributes['amount'] ?? fake()->numberBetween(200, 3000);
            $authority = 'S' . str_pad((string) fake()->numberBetween(1, 9999999), 31, '0', STR_PAD_LEFT) . Str::lower(Str::random(6));
            $refId = (string) fake()->numberBetween(100000000, 999999999);
            $paidTime = now()->subDays(fake()->numberBetween(1, 20))->subHours(fake()->numberBetween(1, 12));

            return [
                'status'          => 'paid',
                'transaction_id'  => $refId,
                'paid_at'         => $paidTime,
                'first_response'  => json_encode([
                    'transaction_id' => $authority,
                    'gateway'        => 'zarinpal',
                    'driver_class'   => 'Shetabit\\Multipay\\Drivers\\Zarinpal\\Zarinpal',
                    'amount'         => (int) $amount,
                    'currency'       => 'T',
                    'created_at'     => $paidTime->copy()->subMinutes(2)->format('Y-m-d H:i:s'),
                ]),
                'second_response' => json_encode([
                    'reference_id' => $refId,
                    'driver'       => 'zarinpal',
                    'amount'       => (int) $amount,
                    'details'      => [
                        'code'      => 100,
                        'message'   => 'Paid',
                        'card_hash' => strtoupper(hash('sha256', Str::random(10))),
                        'card_pan'  => '999999******' . fake()->numberBetween(1000, 9999),
                        'ref_id'    => (int) $refId,
                        'fee_type'  => 'Merchant',
                        'fee'       => 1000,
                        'order_id'  => null,
                    ],
                ]),
            ];
        });
    }


    public function failed(): static
    {
        return $this->state(function (array $attributes) {
            $amount = $attributes['amount'] ?? fake()->numberBetween(100, 2000);
            $authority = 'S' . str_pad((string) fake()->numberBetween(1, 9999999), 31, '0', STR_PAD_LEFT) . Str::lower(Str::random(6));
            $failTime = now()->subDays(fake()->numberBetween(1, 15))->subMinutes(fake()->numberBetween(10, 300));

            return [
                'status'          => 'failed',
                'transaction_id'  => null,
                'paid_at'         => null,
                'first_response'  => json_encode([
                    'transaction_id' => $authority,
                    'gateway'        => 'zarinpal',
                    'driver_class'   => 'Shetabit\\Multipay\\Drivers\\Zarinpal\\Zarinpal',
                    'amount'         => (int) $amount,
                    'currency'       => 'T',
                    'created_at'     => $failTime->copy()->subSeconds(45)->format('Y-m-d H:i:s'),
                ]),
                'second_response' => json_encode([
                    'status'     => 'NOK',
                    'authority'  => $authority,
                    'time'       => $failTime->format('Y-m-d H:i:s'),
                    'ip'         => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36',
                ]),
            ];
        });
    }

    public function returned(): static
    {
        return $this->state(function (array $attributes) {
            $amount = $attributes['amount'] ?? fake()->numberBetween(150, 2500);
            $authority = 'S' . str_pad((string) fake()->numberBetween(1, 9999999), 31, '0', STR_PAD_LEFT) . Str::lower(Str::random(6));
            $refId = (string) fake()->numberBetween(100000000, 999999999);
            $paidTime = now()->subDays(fake()->numberBetween(10, 30));

            return [
                'status'          => 'returned',
                'transaction_id'  => $refId,
                'paid_at'         => $paidTime,
                'first_response'  => json_encode([
                    'transaction_id' => $authority,
                    'gateway'        => 'zarinpal',
                    'driver_class'   => 'Shetabit\\Multipay\\Drivers\\Zarinpal\\Zarinpal',
                    'amount'         => (int) $amount,
                    'currency'       => 'T',
                    'created_at'     => $paidTime->copy()->subMinutes(3)->format('Y-m-d H:i:s'),
                ]),
                'second_response' => json_encode([
                    'reference_id' => $refId,
                    'driver'       => 'zarinpal',
                    'amount'       => (int) $amount,
                    'details'      => [
                        'code'        => 100,
                        'message'     => 'Paid and subsequently refunded by merchant',
                        'card_hash'   => strtoupper(hash('sha256', Str::random(10))),
                        'card_pan'    => '999999******' . fake()->numberBetween(1000, 9999),
                        'ref_id'      => (int) $refId,
                        'fee_type'    => 'Merchant',
                        'fee'         => 1000,
                        'refund_note' => 'Order returned by customer',
                    ],
                ]),
            ];
        });
    }
}
