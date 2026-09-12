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

                // آزادسازی دقیق رزرو هر انبار بر اساس تخصیص‌ ها
                foreach ($item->allocations as $allocation) {
                    $warehouseVariant = WarehouseVariant::lockForUpdate()->findOrFail($allocation->warehouse_variant_id);

                    if ($warehouseVariant) {
                        $warehouseVariant->reserved = max(0, $warehouseVariant->reserved - $allocation->quantity);
                        $warehouseVariant->save();
                    }
                }

                // حذف رکوردهای تخصیص انبار
                $item->allocations()->delete();

                // حذف آیتم سبد
                $item->delete();
            });
        }

        Log::info('Expired carts cleared: ' . $expiredItems->count());
    }
}
