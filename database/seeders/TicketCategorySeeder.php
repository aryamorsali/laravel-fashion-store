<?php

namespace Database\Seeders;

use App\Models\Ticket\TicketCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ticketCategories = [
            [
                'id'     => 1,
                'name'   => 'Order Tracking & Shipping',
                'status' => 1,
            ],
            [
                'id'     => 2,
                'name'   => 'Returns & Refunds',
                'status' => 1,
            ],
            [
                'id'     => 3,
                'name'   => 'Payments & Financial Issues',
                'status' => 1,
            ],
            [
                'id'     => 4,
                'name'   => 'Product Inquiries & Sizing',
                'status' => 1,
            ],
            [
                'id'     => 5,
                'name'   => 'Account & Security',
                'status' => 1,
            ],
            [
                'id'     => 6,
                'name'   => 'Technical Support & Website Issues',
                'status' => 1,
            ],
        ];

        foreach ($ticketCategories as $category) {
            TicketCategory::updateOrCreate(
                ['id' => $category['id']],
                $category
            );
        }
    }
}
