<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Market\CartItem;
use App\Models\Market\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartManager
{
    protected $cartInventoryAllocator;

    public function __construct(CartInventoryAllocator $cartInventoryAllocator)
    {
        $this->cartInventoryAllocator = $cartInventoryAllocator;
    }


    public function addToCart($data)
    {
        return DB::transaction(function () use ($data) {
            // چک کردن موجودی واریانت
            $variant = ProductVariant::lockForUpdate()->findOrFail($data['variant_id']);


            if ($data['quantity'] > $variant->availableStock()) {
                throw new InsufficientStockException('Sorry, there isn’t enough stock for this item.');
            }


            $cartItem = CartItem::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_variant_id' => $variant->id,
                ],
                [
                    'quantity' => 0,       //  بعدا در سرویس مقدارشو تغییر میدهیم
                ]
            );

            $this->cartInventoryAllocator->reallocate($cartItem, $data['quantity']);

            return $cartItem;
        });
    }
}
