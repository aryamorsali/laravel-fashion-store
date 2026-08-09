<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\AddToCartRequest;
use App\Models\Market\CartItem;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use App\Models\Market\CouponUser;
use App\Models\Market\ProductVariant;
use App\Models\Market\WarehouseVariant;
use App\Services\CartCalculator;
use App\Services\CartInventoryAllocator;
use App\Services\CartManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    protected $cartManager;

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }

    public function shopingCart(CartCalculator $cartCalculator)
    {
        $cartItems = CartItem::with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
            'productVariant.amazingSale',
        ])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        $commonDiscount = CommonDiscount::where('status', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

        $coupon = Coupon::where('code', session('applied_coupon'))
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        // چک کردن عمومی یا خصوصی بودن کوپن
        if ($coupon && $coupon->type == 1) {
            if ($coupon->user_id != Auth::id()) {
                session()->forget('applied_coupon');
            }
        }

        // -------------------------
        // محاسبه کل سبد خرید
        // -------------------------

        $totals = $cartCalculator->calculateCartTotals($cartItems, $commonDiscount, session('applied_coupon'));

        return view('customer.sales-process.shoping-cart', compact(
            'cartItems',
            'commonDiscount',
            'totals',
        ));
    }


    // public function addToCart(AddToCartRequest $request, CartInventoryAllocator $cartInventoryAllocator)
    // {
    //     $data = $request->validated();
    //     try {
    //         DB::transaction(function () use ($data, $cartInventoryAllocator) {
    //             // چک کردن موجودی واریانت
    //             $variant = ProductVariant::lockForUpdate()->findOrFail($data['variant_id']);


    //             if ($data['quantity'] > $variant->availableStock()) {
    //                 throw new \Exception();
    //             }

    //             $cartItem = CartItem::updateOrCreate(
    //                 [
    //                     'user_id' => Auth::id(),
    //                     'product_variant_id' => $variant->id,
    //                 ],
    //                 [
    //                     'quantity' => 0,       //  بعدا در سرویس مقدارشو تغییر میدهیم
    //                 ]
    //             );

    //             $cartInventoryAllocator->reallocate($cartItem, $data['quantity']);
    //         });
    //     } catch (\Exception $e) {
    //         return back()->with(
    //             'toast-error',
    //             'Sorry, there isn’t enough stock for this item.'
    //         );
    //     }
    //     return redirect()->back()->with(
    //         'toast-success',
    //         'Product successfully added to your cart !'
    //     );
    // }



    public function addToCart(AddToCartRequest $request)
    {
        $data = $request->validated();
        try {
            
            $this->cartManager->addToCart($data);

        } catch (\Exception $e) {
            return back()->with(
                'toast-error',
                'Sorry, there isn’t enough stock for this item.'
            );
        }
        return redirect()->back()->with(
            'toast-success',
            'Product successfully added to your cart !'
        );
    }

    public function removeFromCart(CartItem $cartItem)
    {
        if ($cartItem->user_id === Auth::user()->id) {
            DB::transaction(function () use ($cartItem) {

                if ($cartItem->allocations()->whereNotNull('order_item_id')->exists()) {
                    return back()->with(
                        'toast-error',
                        'This item has entered the checkout process and cannot be removed.'
                    );
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

            return back();
        } else {
            abort(403);
        }
    }

    public function updateCart(Request $request, CartCalculator $cartCalculator, CartInventoryAllocator $cartInventoryAllocator)
    {
        try {
            return DB::transaction(function () use ($request, $cartCalculator, $cartInventoryAllocator) {
                $cartItem = CartItem::where('id', $request->cart_item_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                $variant = $cartItem->productVariant;

                $newQuantity = max((int)$request->quantity, 1);
                $oldQuantity = $cartItem->quantity;
                $diff = $newQuantity - $oldQuantity;

                // -------------------------
                // محدودیت گزاشتن روی تعداد واریانت رزرو شده
                // -------------------------
                if ($newQuantity < 1) {
                    $newQuantity = 1;
                }
                $availableStock = $variant->availableStock();

                $maxAllowed = min(10, $availableStock + $oldQuantity);

                if ($newQuantity > $maxAllowed) {
                    return response()->json([
                        'status'    => 'stock_error',
                        'available' => $maxAllowed,
                    ]);
                }
                // -------------------------
                // بررسی موجودی (فقط وقتی تعداد رو زیاد می‌کنیم)
                // -------------------------

                if ($diff > 0) {


                    if ($diff > $availableStock) {
                        return response()->json([
                            'status'    => 'stock_error',
                            'available' => $availableStock + $oldQuantity,
                        ]);
                    }
                }

                // -------------------------
                // آپدیت reserved
                // -------------------------

                $cartInventoryAllocator->reallocate($cartItem, $newQuantity);

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
                    return response()->json([
                        'status'    => 'error',
                        'message' => 'empty',
                    ]);
                }

                // -------------------------
                // محاسبه کل سبد خرید
                // -------------------------

                $totals = $cartCalculator->calculateCartTotals($cartItems, $commonDiscount, session('applied_coupon'));

                return response()->json([
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
                ]);
            });
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function coupon(Request $request, CartCalculator $cartCalculator)
    {
        $data = $request->validate([
            'coupon' => 'required|max:120|min:2'
        ]);

        $coupon = Coupon::where('code', $data['coupon'])
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon) {
            return response()->json([
                'status' => 'error',
                'message' => 'invalid code',
            ]);
        }


        // بررسی استفاده قبلی
        $alreadyUsed = CouponUser::where('user_id', Auth::id())
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($alreadyUsed) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already used this discount code'
            ]);
        }


        // چک کردن عمومی یا خصوصی بودن کوپن
        if ($coupon->type == 1) {
            if ($coupon->user_id != Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This discount code is specific to a specific user'
                ]);
            }
        }

        // محاسبه سبد خرید

        $cartItems = CartItem::where('user_id', Auth::user()->id)
            ->with('productVariant.amazingSale')
            ->get();


        $commonDiscount = CommonDiscount::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();


        if ($cartItems->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Cart is empty'], 422);
        }

        $totals = $cartCalculator->calculateCartTotals($cartItems, $commonDiscount, $coupon->code);

        // سشن برای ذخیره کوپن
        session(['applied_coupon' => $coupon->code]);

        return response()->json([
            'status' => 'success',
            'finalPrice' => number_format($totals['totalCartPrice'], 2),
            'couponDiscountAmount' => number_format($totals['couponDiscount'], 2),
        ]);
    }
}
