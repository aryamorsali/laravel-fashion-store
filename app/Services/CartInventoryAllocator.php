<?php

namespace App\Services;

use App\Models\Market\CartItem;
use App\Models\Market\InventoryAllocation;
use App\Models\Market\WarehouseVariant;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartInventoryAllocator
{
    public function reallocate(CartItem $cartItem, int $newQuantity)
    {

        if ($newQuantity <= 0) {
            throw new RuntimeException('Invalid quantity.');
        }

        $warehouseVariants = WarehouseVariant::query()
            ->where('product_variant_id', $cartItem->product_variant_id)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        $allocations = InventoryAllocation::where('cart_item_id', $cartItem->id)
            ->whereNull('order_item_id')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($allocations as $allocation) {
            $warehouseVariant = $warehouseVariants->get($allocation->warehouse_variant_id);

            $warehouseVariant->reserved = max(
                0,
                $warehouseVariant->reserved - $allocation->quantity
            );

            $warehouseVariant->save();
        }

        InventoryAllocation::whereIn('id', $allocations->pluck('id'))->delete();

        $warehouseVariants = WarehouseVariant::query()
            ->where('product_variant_id', $cartItem->product_variant_id)
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        $remaining = $newQuantity;

        foreach ($warehouseVariants as $warehouseVariant) {
            if ($remaining <= 0) {
                break;
            }

            $available = $warehouseVariant->stock - $warehouseVariant->reserved;

            // اگر این انبار موجودی آزاد نداشت برو دنبال انبار بعدی
            if ($available <= 0) {
                continue;
            }

            $take = min($remaining, $available);

            $warehouseVariant->reserved += $take;
            $warehouseVariant->save();

            $cartItem->allocations()->create([
                'warehouse_variant_id' => $warehouseVariant->id,
                'quantity' => $take,
            ]);

            $remaining -= $take;
        }

        if ($remaining > 0) {
            throw new RuntimeException('Insufficient stock.');
        }

        $cartItem->update([
            'quantity' => $newQuantity,
            'expires_at' => now()->addMinutes(30)
        ]);
    }
}
