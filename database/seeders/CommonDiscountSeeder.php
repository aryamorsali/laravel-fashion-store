<?php

namespace Database\Seeders;

use App\Models\Market\CommonDiscount;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommonDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commonDiscounts = [
            [
                'id'                   => 1,
                'title'                => 'Black Friday Super Sale',
                'percentage'           => 20,
                'discount_ceiling'     => 50, 
                'minimal_order_amount' => 100, 
                'status'               => 1,   
                'start_date'           => Carbon::now()->subDays(2),
                'end_date'             => Carbon::now()->addMonths(1),
            ],
            [
                'id'                   => 2,
                'title'                => 'Summer Launch Promo',
                'percentage'           => 15,
                'discount_ceiling'     => 30,  
                'minimal_order_amount' => 80, 
                'status'               => 0, 
                'start_date'           => Carbon::now()->subDays(1),
                'end_date'             => Carbon::now()->addMonths(1),
            ],
            [
                'id'                   => 3,
                'title'                => 'Winter Flash Sale',
                'percentage'           => 10,
                'discount_ceiling'     => 15, 
                'minimal_order_amount' => 50,  
                'status'               => 2,  
                'start_date'           => Carbon::now()->subMonths(3),
                'end_date'             => Carbon::now()->subMonths(2),
            ],
        ];

        foreach ($commonDiscounts as $discount) {
            CommonDiscount::updateOrCreate(
                ['id' => $discount['id']],
                $discount
            );
        }
    }
}
