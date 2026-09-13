<?php

namespace App\Console\Commands;

use App\Models\Market\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AbandonedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:abandoned-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel unpaid expired orders and detach inventory allocations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::whereIn('order_status', ['not_checked', 'awaiting_confirmation'])->whereIn('payment_status', ['unpaid', 'failed'])
            ->where('created_at', '<=', now()->subMinutes(15))->get();


        foreach ($orders as $order) {
            DB::transaction(function () use ($order) {

                $order = Order::where('id', $order->id)->lockForUpdate()->first();

                // update status
                $order->update([
                    'order_status' => 'canceled',
                    'payment_status' => 'failed',
                ]);

                $order->payments()->where('status', 'unpaid')->update(['status' => 'failed']);

                $order->load('orderItems.allocations');

                foreach ($order->orderItems as $item) {
                    $item->allocations()->update(['order_item_id' => null]);
                }
            });
        }
    }
}
