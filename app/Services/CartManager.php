<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\Market\CartItem;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use App\Models\Market\ProductVariant;
use App\Models\Market\WarehouseVariant;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PharIo\Manifest\Extension;
use Illuminate\Validation\ValidationException;

class CartManager
{
    protected $cartInventoryAllocator;
    protected $cartCalculator;

    public function __construct(CartInventoryAllocator $cartInventoryAllocator, CartCalculator $cartCalculator)
    {
        $this->cartInventoryAllocator = $cartInventoryAllocator;
        $this->cartCalculator = $cartCalculator;
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


    public function shopingCart($couponCode = null)
    {
        $code = $couponCode ?? session('applied_coupon');

        $cartItems = CartItem::with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
            'productVariant.amazingSale',
        ])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        $commonDiscount = CommonDiscount::where('status', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

        $coupon = null;

        if ($code) {
            $coupon = Coupon::where('code', $code)
                ->where('status', 1)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if (!$coupon) {
                if ($couponCode === null) {
                    session()->forget('applied_coupon');
                }

                throw ValidationException::withMessages([
                    'coupon' => 'The coupon code is invalid.',
                ]);
            }

            // کوپن خصوصی است و متعلق به کاربر فعلی نیست
            elseif ($coupon->type == 1 && $coupon->user_id != Auth::id()) {
                if ($couponCode === null) {
                    session()->forget('applied_coupon');
                }

                $coupon = null;
            }
        }

        // -------------------------
        // محاسبه کل سبد خرید
        // -------------------------

        $totals = $this->cartCalculator->calculateCartTotals($cartItems, $commonDiscount, $coupon ? $coupon->code : null);

        return [
            'cartItems' => $cartItems,
            'commonDiscount' => $commonDiscount,
            'coupon' => $coupon,
            'totals' => $totals
        ];
    }

    public function removeFromCart(CartItem $cartItem)
    {
        DB::transaction(function () use ($cartItem) {

            if ($cartItem->allocations()->whereNotNull('order_item_id')->exists()) {
                throw new \DomainException('This item is in an active payment process and cannot be deleted at this time. If you wish to cancel, cancel the payment; otherwise, the item will be available for deletion after the payment deadline.');
            }

            foreach ($cartItem->allocations as $allocation) {
                $warehouseVariant = WarehouseVariant::query()
                    ->lockForUpdate()
                    ->findOrFail($allocation->warehouse_variant_id);

                $warehouseVariant->reserved = max(
                    0,
                    $warehouseVariant->reserved - $allocation->quantity
                );

                $warehouseVariant->save();
            }

            $cartItem->allocations()->delete();

            $cartItem->delete();
        });
    }
}
