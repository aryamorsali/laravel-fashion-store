<?php

namespace App\Console\Commands;

use App\Models\Market\CartItem;
use App\Models\Market\WarehouseVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-expired-carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredItems = CartItem::where('expires_at', '<', now())->get();

        foreach ($expiredItems as $item) {

            $warehouse = WarehouseVariant::where('product_variant_id', $item->product_variant_id)
                ->first();

            if ($warehouse) {
                // آزاد کردن رزرو
                $warehouse->reserved -= $item->quantity;

                if ($warehouse->reserved < 0) {
                    $warehouse->reserved = 0;
                }

                $warehouse->save();
            }

            // حذف آیتم سبد
            $item->delete();
        }

        Log::info('Expired carts cleared: ' . $expiredItems->count());
    }
}
