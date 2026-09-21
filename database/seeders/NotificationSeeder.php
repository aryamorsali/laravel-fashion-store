<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_owner', 1)->first();

        $notifications = [
            // سفارش جدید
            [
                'type' => 'App\Notifications\NewOrderRegisteredNotification',
                'data' => [
                    'event'   => 'new_order',
                    'message' => 'New order <b>#1042</b> received',
                    'url'     => route('admin.market.order.show', 1, false),
                    'meta'    => [
                        'order_id'      => 1042,
                        'customer_name' => 'Sara Mohammadi',
                    ],
                ],
                'read_at' => null, 
            ],

            // کسری موجودی انبار
            [
                'type' => 'App\Notifications\LowStockNotification',
                'data' => [
                    'event'   => 'low_stock',
                    'message' => 'Low inventory: <b>Black Coza T-Shirt — L</b>',
                    'url'     => route('admin.market.warehouse.index', [], false),
                    'meta'    => [
                        'count' => 1,
                        'items' => [
                            [
                                'warehouse_id'       => 1,
                                'product_variant_id' => 12,
                                'name'               => 'Coza T-Shirt',
                                'color'              => 'Black',
                                'size'               => 'L',
                            ],
                        ],
                    ],
                ],
                'read_at' => null, 
            ],

            // تیکت پشتیبانی جدید
            [
                'type' => 'App\Notifications\NewTicketRegisteredNotification',
                'data' => [
                    'event'   => 'new_ticket',
                    'message' => 'New support ticket <b>#48</b>',
                    'url'     => route('admin.ticket.show', 1, false),
                ],
                'read_at' => null,
            ],

            // ثبت‌ نام کاربر جدید
            [
                'type' => 'App\Notifications\NewUserRegisteredNotification',
                'data' => [
                    'event'   => 'new_user',
                    'message' => 'New user registered: <b>Ali Rezaei</b>',
                    'url'     => route('admin.user.customer.index', [], false),
                ],
                'read_at' => null, 
            ],

            // تراکنش ناموفق
            [
                'type' => 'App\Notifications\PaymentFailedNotification',
                'data' => [
                    'event'   => 'payment_failed',
                    'message' => 'Payment failed for order #1039',
                    'url'     => route('admin.market.payment.show', 1, false),
                    'meta'    => [
                        'order_id'      => 1039,
                        'customer_name' => 'Reza Rad',
                    ],
                ],
                'read_at' => now()->subHours(2), 
            ],

            // کامنت محصول جدید
            [
                'type' => 'App\Notifications\NewProductCommentRegisteredNotification',
                'data' => [
                    'event'   => 'new_product_comment',
                    'message' => 'New product comment <b>#25</b>',
                    'url'     => route('admin.market.comment.show', 1, false),
                ],
                'read_at' => now()->subHours(5), 
            ],

            // کامنت پست وبلاگ جدید
            [
                'type' => 'App\Notifications\NewPostCommentRegisteredNotification',
                'data' => [
                    'event'   => 'new_post_comment',
                    'message' => 'New post comment <b>#14</b>',
                    'url'     => route('admin.content.comment.show', 1, false),
                ],
                'read_at' => now()->subDay(),
            ],
        ];

        foreach ($notifications as $index => $item) {
            DB::table('notifications')->insert([
                'id'              =>  Str::uuid(),
                'type'            => $item['type'],
                'notifiable_type' => User::class,
                'notifiable_id'   => $admin->id,
                'data'            => json_encode($item['data']),
                'read_at'         => $item['read_at'],
                'created_at'      => now()->subMinutes(($index + 1) * 20),
                'updated_at'      => now()->subMinutes(($index + 1) * 20),
            ]);
        }
    }
}
