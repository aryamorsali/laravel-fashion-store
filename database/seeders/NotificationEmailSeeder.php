<?php

namespace Database\Seeders;

use App\Models\Notification\Email;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = [
            [
                'id'           => 1,
                'subject'      => 'Welcome to Our Premium Store! 🎉',
                'body'         => '<p>Hi there,</p><p>Thank you for joining our community! Enjoy <strong>10% OFF</strong> on your first order using code <code>WELCOME10</code> at checkout.</p>',
                'status'       => 'sent',
                'published_at' => Carbon::now()->subDays(10),
            ],
            [
                'id'           => 2,
                'subject'      => 'Black Friday Mega Sale - Up to 50% OFF',
                'body'         => '<p>Our biggest sale of the year is officially live. Explore exclusive deals on footwear, apparel, and accessories.</p>',
                'status'       => 'sent',
                'published_at' => Carbon::now()->subDays(1),
            ],
            [
                'id'           => 4,
                'subject'      => 'Exclusive VIP Winter Preview [Draft]',
                'body'         => '<p>Get early access to our winter collection before it hits the public catalog.</p>',
                'status'       => 'draft',
                'published_at' => null, // هنوز منتشر نشده
            ],
        ];

        foreach ($emails as $email) {
            Email::updateOrCreate(
                ['id' => $email['id']],
                $email
            );
        }
    }
}
