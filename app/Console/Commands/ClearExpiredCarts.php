<?php

namespace App\Console\Commands;

use App\Models\Market\CartItem;
use App\Models\Market\WarehouseVariant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClearExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:clear-expired-carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear expired cart items and release reserved warehouse inventory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredItems = CartItem::with(['allocations'])->where('expires_at', '<', now())->get();

        foreach ($expiredItems as $item) {
            DB::transaction(function () use ($item) {

                $cartItem = CartItem::where('id', $item->id)->lockForUpdate()->first();

                $allocations = $cartItem->allocations()->whereNull('order_item_id')->get();

                if ($allocations->isNotEmpty()) {
                    // آزادسازی دقیق رزرو هر انبار بر اساس تخصیص‌ ها
                    foreach ($allocations as $allocation) {
                        $warehouseVariant = WarehouseVariant::lockForUpdate()->findOrFail($allocation->warehouse_variant_id);

                        if ($warehouseVariant) {
                            $warehouseVariant->reserved = max(0, $warehouseVariant->reserved - $allocation->quantity);
                            $warehouseVariant->save();
                        }
                    }

                    // حذف رکوردهای تخصیص انبار
                    $cartItem->allocations() ->whereNull('order_item_id')->delete();
                    
                    // حذف آیتم سبد
                    $cartItem->delete();
                }
            });
        }

        Log::info('Expired carts cleared: ' . $expiredItems->count());
    }
}
