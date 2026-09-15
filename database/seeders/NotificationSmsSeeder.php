<?php

namespace Database\Seeders;

use App\Models\Notification\SMS;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $smses = [
            [
                'id'           => 1,
                'title'        => 'Welcome Promo',
                'body'         => 'Welcome to Aria Shop! Use code WELCOME10 for 10% OFF on your first purchase: https://aryamorsali.com',
                'status'       => 'sent',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'id'           => 2,
                'title'        => 'Weekend Flash Sale',
                'body'         => 'Flash Sale Alert! Up to 40% discount on all sneakers this weekend. Shop now: https://aryamorsali.com/sale',
                'status'       => 'sent',
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'id'           => 3,
                'title'        => 'Free Express Shipping Promo',
                'body'         => 'Free express delivery on all orders over $150 today only! Arya Shop',
                'status'       => 'queued',
                'published_at' => Carbon::now()->addHours(4),
            ],
            [
                'id'           => 4,
                'title'        => 'Cart Abandonment Reminder',
                'body'         => 'You left items in your cart! Complete your order now and get 5% OFF: https://aryamorsali.com/cart',
                'status'       => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($smses as $sms) {
            SMS::updateOrCreate(
                ['id' => $sms['id']],
                $sms
            );
        }
    }
}
