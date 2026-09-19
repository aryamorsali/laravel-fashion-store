<?php

namespace Database\Factories\Market;

use App\Models\Market\ProductVariant;
use App\Models\Market\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Market\WarehouseTransaction>
 */
class WarehouseTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_id'       => Warehouse::inRandomOrder()->value('id'),
            'product_variant_id' => ProductVariant::inRandomOrder()->value('id'),
            'type'               => fake()->randomElement(['in', 'out', 'return']),
            'quantity'           => fake()->numberBetween(1, 20),
            'unit_price'         => fake()->numberBetween(500, 10000),
        ];
    }
}
