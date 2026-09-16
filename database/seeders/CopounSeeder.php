<?php

namespace Database\Seeders;

use App\Models\Market\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CopounSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::inRandomOrder()->value('id');

        $coupons = [
            // ۱. کوپن عمومی درصدی (فعال) - مناسب برای تست کدهای تخفیف همگانی
            [
                'code'             => 'WELCOME20',
                'amount'           => 20,
                'amount_type'      => 0,    // percentage
                'discount_ceiling' => 50,
                'type'             => 0,
                'user_id'          => null,
                'status'           => 1,
                'start_date'       => Carbon::now()->subDays(2),
                'end_date'         => Carbon::now()->addMonths(1),
            ],

            [
                'code'             => 'AryaMorsali',
                'amount'           => 15,
                'amount_type'      => 1,     // price
                'discount_ceiling' => null,
                'type'             => 0,
                'user_id'          => null,
                'status'           => 1,     // 1 => active
                'start_date'       => Carbon::now()->subDays(5),
                'end_date'         => Carbon::now()->addWeeks(2),
            ],

            [
                'code'             => 'SPECIALVIP',
                'amount'           => 30,
                'amount_type'      => 0,    
                'discount_ceiling' => 100,
                'type'             => 1,    
                'user_id'          => $userId,
                'status'           => 1,    
                'start_date'       => Carbon::now()->subDay(),
                'end_date'         => Carbon::now()->addDays(10),
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
