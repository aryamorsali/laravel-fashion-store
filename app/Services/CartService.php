<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Exceptions\CartItemActiveGatewayException;
use App\Exceptions\CouponException;
use App\Models\Market\CartItem;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use App\Models\Market\CouponUser;
use App\Models\Market\InventoryAllocation;
use App\Models\Market\ProductVariant;
use App\Models\Market\WarehouseVariant;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PharIo\Manifest\Extension;
use Illuminate\Validation\ValidationException;

class CartService
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
                throw ValidationException::withMessages([
                    'quantity' => 'Sorry, there isn’t enough stock for this item.'
                ])->redirectTo(route('customer.sales-process.add-to-cart'));
            }

            $existingCartItem = CartItem::where('user_id', Auth::id())->where('product_variant_id', $variant->id)->lockForUpdate()->first();

            // اگر آیتم سبد خرید در درگاه پرداخت فعال بود
            if ($existingCartItem && $existingCartItem->allocations()->whereNotNull('order_item_id')->exists()) {
                throw new \DomainException('This item is currently in the checkout process and cannot be updated. An update for this item will be available after the checkout period ends.');
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

            $cartItem = CartItem::where('id', $cartItem->id)
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            if ($cartItem->allocations()->whereNotNull('order_item_id')->exists()) {
                throw new \DomainException('This item is in an active payment process and cannot be deleted at this time. 
                If you wish to cancel, cancel the payment; otherwise, the item will be available for deletion after the payment deadline.');
            }

            $allocations = InventoryAllocation::where('cart_item_id', $cartItem->id)
                ->whereNull('order_item_id')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();


            $warehouseVariants = WarehouseVariant::whereIn(
                'id',
                $allocations->pluck('warehouse_variant_id')->unique()->sort()->values()
            )
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');


            foreach ($allocations as $allocation) {
                $warehouseVariant = $warehouseVariants->get($allocation->warehouse_variant_id);

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



    public function updateCart($data, $couponCode = null)
    {
        return DB::transaction(function () use ($data, $couponCode) {
            $cartItem = CartItem::where('id', $data['cart_item_id'])
                ->where('user_id', Auth::id())
                ->lockForUpdate()
                ->firstOrFail();

            // اگر آیتم سبد خرید در درگاه پرداخت فعال بود
            if ($cartItem && $cartItem->allocations()->whereNotNull('order_item_id')->exists()) {
                throw new CartItemActiveGatewayException(
                    message: 'This item is currently in the checkout process and cannot be updated. An update for this item will be available after the checkout period ends.',
                    previousValue: $cartItem->quantity,
                );
            }

            $variant = $cartItem->productVariant;

            $newQuantity = (int) $data['quantity'];

            $oldQuantity = $cartItem->quantity;

            // -------------------------
            // محدودیت گزاشتن روی تعداد واریانت رزرو شده
            // -------------------------
            $availableStock = $variant->availableStock();

            $maxAllowed = min(10, $availableStock + $oldQuantity);

            if ($newQuantity > $maxAllowed) {
                throw new CartException(
                    message: 'Requested quantity is not available.',
                    available: $maxAllowed
                );
            }

            // -------------------------
            // آپدیت reserved
            // -------------------------

            $this->cartInventoryAllocator->reallocate($cartItem, $newQuantity);

            // -------------------------
            // آپدیت تعداد محصول Product prices(x)
            // -------------------------

            $totalProductsQuantity = CartItem::where('user_id', Auth::id())->sum('quantity');

            // -------------------------
            //   محاسبه قیمت تک آیتم
            // -------------------------

            $price = $variant->price;
            $finalPrice = $price;
            $discount = null;

            $sale = $variant->amazingSale;

            if (
                $sale &&
                $sale->is_active &&
                $sale->start_date <= now() &&
                $sale->end_date >= now()
            ) {
                $discount = $sale->percentage;
                $finalPrice = $price - ($price * $discount) / 100;
            }
            $totalItemPrice = $finalPrice * $newQuantity;


            // -------------------------
            // محاسبه کل سبد خرید
            // -------------------------
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with('productVariant.amazingSale')
                ->get();


            $commonDiscount = CommonDiscount::where('status', 1)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();


            // -------------------------
            // محاسبه کل سبد خرید
            // -------------------------

            $totals = $this->cartCalculator->calculateCartTotals($cartItems, $commonDiscount, $couponCode);

            return [
                // آیتم تغییرکرده
                'totalItemPrice' => (float) $totalItemPrice,
                'price' => (float) $price,
                'finalPrice' => (float) $finalPrice,
                'discount' => $discount ? (float) $discount : null,

                // مقادیر لازم برای هدر سبد
                'cart_item_id' => (int) $cartItem->id,
                'new_quantity' => (int) $newQuantity,
                'totalProductsQuantity' => (int) $totalProductsQuantity,

                // مجموع سبد
                'totalCartPrice' => (float) $totals['totalCartPrice'],
                'productPrices' => (float) $totals['productPrices'],
                'productDiscounts' => (float) $totals['productDiscounts'],

                // تخفیف عمومی
                'commonDiscountAmount' => (float) $totals['commonDiscountAmount'],
                'commonDiscountPercentage' => (int) $totals['commonDiscountPercentage'],

                // کوپن
                'couponApplied' => (bool) ($totals['couponDiscount'] > 0),
                'couponDiscount' => (float) $totals['couponDiscount'],
            ];
        });
    }



    public function applyCoupon($data)
    {

        $coupon = Coupon::where('code', $data['coupon'])
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            throw new CouponException('invalid code');
        }


        // بررسی استفاده قبلی
        $alreadyUsed = CouponUser::where('user_id', Auth::id())
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($alreadyUsed) {
            throw new CouponException('You have already used this discount code');
        }


        // چک کردن عمومی یا خصوصی بودن کوپن
        if ($coupon->type == 1) {
            if ($coupon->user_id != Auth::id()) {
                throw new CouponException('This discount code is specific to a specific user');
            }
        }

        // محاسبه سبد خرید

        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('productVariant.amazingSale')
            ->get();


        $commonDiscount = CommonDiscount::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();


        if ($cartItems->isEmpty()) {
            throw new CouponException('Cart is empty');
        }

        $totals = $this->cartCalculator->calculateCartTotals($cartItems, $commonDiscount, $coupon->code);

        return [
            'coupon' => $coupon->code,
            'finalPrice' => $totals['totalCartPrice'],
            'couponDiscountAmount' => $totals['couponDiscount'],
        ];
    }
}
