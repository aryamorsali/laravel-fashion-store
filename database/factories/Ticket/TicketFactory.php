<?php

namespace Database\Factories\Ticket;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket\TicketCategory;
use App\Models\Ticket\TicketPriority;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->randomElement([
                'Problem with payment',
                'Not sending tracking code',
                'Request for return of goods',
                'Question about product sizing',
                'Not applying discount code in shopping cart',
                'Problem with login with mobile number',
                'The shipped goods are inconsistent',
                'Tracking the status of the shipment',
            ]),
            'description' => fake()->paragraph(3),
            'user_id'     => User::inRandomOrder()->value('id'),
            'category_id' => TicketCategory::inRandomOrder()->value('id'),
            'priority_id' => TicketPriority::inRandomOrder()->value('id'),
            'parent_id'   => null,
            'status'      => fake()->randomElement([0, 0, 1]),
            'seen'        => fake()->randomElement([0, 1]),
        ];
    }
}
