<?php

namespace App\Services;

use App\Exceptions\CartException;
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
                throw ValidationException::withMessages([
                    'quantity' => 'Sorry, there isn’t enough stock for this item.'
                ])->redirectTo(route('customer.sales-process.add-to-cart'));
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

    

    public function updateCart($data)
    {
        return DB::transaction(function () use ($data) {
            $cartItem = CartItem::where('id', $data['cart_item_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $variant = $cartItem->productVariant;

            $newQuantity = (int) $data['quantity'];

            $oldQuantity = $cartItem->quantity;
            $diff = $newQuantity - $oldQuantity;

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
            // بررسی موجودی (فقط وقتی تعداد رو زیاد می‌کنیم)
            // -------------------------

            if ($diff > 0) {

                if ($diff > $availableStock) {
                    throw new CartException(
                        message: 'Requested quantity is not available.',
                        available: $availableStock + $oldQuantity
                    );
                }
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
            $cartItems = CartItem::where('user_id', Auth::user()->id)
                ->with('productVariant.amazingSale')
                ->get();


            $commonDiscount = CommonDiscount::where('status', 1)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();


            if ($cartItems->isEmpty()) {
                throw new \DomainException('Cart is empty.');
            }

            // -------------------------
            // محاسبه کل سبد خرید
            // -------------------------

            $totals = $this->cartCalculator->calculateCartTotals($cartItems, $commonDiscount, session('applied_coupon'));

            return [
                'status' => 'success',
                // آیتم
                'totalItemPrice' => number_format($totalItemPrice, 2),
                'price' => number_format($price, 2),
                'finalPrice' => number_format($finalPrice, 2),
                'discount' => $discount,

                // مقادیر برای آپدیت هدر کارت
                'cart_item_id' => $cartItem->id,
                'new_quantity' => $newQuantity,
                'totalProductsQuantity' => $totalProductsQuantity,

                // کل سبد
                'totalCartPrice' => number_format($totals['totalCartPrice'], 2),
                'productPrices' => number_format($totals['productPrices'], 2),
                'productDiscounts' => number_format($totals['productDiscounts'], 2),

                // تخفیف عمومی
                'commonDiscountAmount' => number_format($totals['commonDiscountAmount'], 2),
                'commonDiscountPercentage' => $totals['commonDiscountPercentage'],

                // کوپن تخفیف
                'couponApplied' => $totals['couponDiscount'] > 0,
                'couponDiscount' => number_format($totals['couponDiscount'], 2),
            ];
        });
    }
}
