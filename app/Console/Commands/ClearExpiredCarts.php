<?php

namespace App\Console\Commands;

use App\Models\Market\CartItem;
use App\Models\Market\InventoryAllocation;
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
        $expiredItems = CartItem::where('expires_at', '<', now())->get();

        foreach ($expiredItems as $item) {
            DB::transaction(function () use ($item) {
                // برای جلوگیری از deadlock
                // ترتیب قفل‌ ها: cart_items بعد inventory_allocations بعد warehouse_variants
                $cartItem = CartItem::where('id', $item->id)->lockForUpdate()->first();

                if (!$cartItem) {
                    return;
                }

                $allocations = InventoryAllocation::where('cart_item_id', $cartItem->id)
                    ->whereNull('order_item_id')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();


                if ($allocations->isNotEmpty()) {
                    // دریافت آیدی انبارها و قفل کردن مرتب آن‌ها برای جلوگیری از ددلاک
                    $warehouseVariantIds = $allocations->pluck('warehouse_variant_id')->unique()->sort();

                    $warehouseVariants = WarehouseVariant::whereIn('id', $warehouseVariantIds)
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    // آزادسازی دقیق رزرو هر انبار بر اساس تخصیص‌ ها
                    foreach ($allocations as $allocation) {
                        $warehouseVariant = $warehouseVariants->firstWhere('id', $allocation->warehouse_variant_id);

                        if ($warehouseVariant) {
                            $warehouseVariant->reserved = max(0, $warehouseVariant->reserved - $allocation->quantity);
                            $warehouseVariant->save();
                        }
                    }

                    // حذف رکوردهای تخصیص انبار
                    $cartItem->allocations()->whereNull('order_item_id')->delete();

                    // حذف آیتم سبد
                    $cartItem->delete();
                }
            });
        }

        Log::info('Expired carts cleared: ' . $expiredItems->count());
    }
}
