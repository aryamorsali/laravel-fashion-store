<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\AddToCartRequest;
use App\Models\Market\CartItem;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use App\Models\Market\ProductVariant;
use App\Models\Market\WarehouseVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function shopingCart()
    {
        if (!Auth::check()) {
            return redirect()->back()->with(
                'toast-success',
                'Please log in to your account first. 
                 <a href="' . route('auth.login-register.form') . '" class="toast-link">Login / Register</a>'
            );
        }

        $cartItems = CartItem::with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size',
            'productVariant.amazingSale',
        ])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        $commonDiscount = CommonDiscount::where('status', 1)->where('start_date', '<', now())->where('end_date', '>', now())->first();


        return view('customer.sales-process.shoping-cart', compact(
            'cartItems',
            'commonDiscount'
        ));
    }




    public function addToCart(AddToCartRequest $request)
    {
        if (!Auth::check()) {
            return redirect()->back()->with(
                'toast-success',
                'Please log in to your account first. 
                 <a href="' . route('auth.login-register.form') . '" class="toast-link">Login / Register</a>'
            );
        }

        $data = $request->validated();
        try {
            DB::transaction(function () use ($data) {
                // چک کردن موجودی واریانت
                $variant = ProductVariant::findOrFail($data['variant_id']);

                if ($data['quantity'] > $variant->availableStock()) {
                    throw new \Exception();
                }

                $cartItem = CartItem::where('user_id', Auth::id())
                    ->where('product_variant_id', $variant->id)
                    ->first();

                $oldQty = $cartItem ? $cartItem->quantity : 0;
                $newQty = $data['quantity']; // override


                CartItem::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'product_variant_id' => $variant->id,         // شرط (unique )
                    ],
                    [
                        'quantity' => $newQty,           // فیلدهایی که تغییر میکنن 
                    ]
                );

                //  گرفتن موجودی انبار
                $warehouseVariant = WarehouseVariant::where('product_variant_id', $variant->id)->firstOrFail();

                // فرمول: reserved = reserved - oldQty + newQty
                $warehouseVariant->reserved = $warehouseVariant->reserved - $oldQty + $newQty;

                // جلوگیری از منفی شدن
                if ($warehouseVariant->reserved < 0) {
                    $warehouseVariant->reserved = 0;
                }
                $warehouseVariant->save();
            });
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

                $warehouseVariant = WarehouseVariant::where('product_variant_id', $cartItem->product_variant_id)->firstOrFail();

                $warehouseVariant->reserved -= $cartItem->quantity;

                if ($warehouseVariant->reserved < 0) {
                    $warehouseVariant->reserved = 0;
                }

                $warehouseVariant->save();

                $cartItem->delete();
            });

            return back();
        }
    }

    // محاسبه سبد
    private function calculateCartTotals($userId)
    {
        $cartItems = CartItem::where('user_id', $userId)
            ->with('productVariant.amazingSale')
            ->get();

        $totalCartPrice = 0;
        $productDiscounts = 0;
        $productPrices = 0;

        foreach ($cartItems as $item) {

            $price = $item->productVariant->price;
            $final = $price;

            $sale = $item->productVariant->amazingSale;

            if (
                $sale &&
                $sale->is_active &&
                $sale->start_date <= now() &&
                $sale->end_date >= now()
            ) {
                $final = $price - ($price * $sale->percentage) / 100;
                $productDiscounts += ($price * $item->quantity * $sale->percentage) / 100;
            }

            $productPrices += $price * $item->quantity;
            $totalCartPrice += $final * $item->quantity;
        }

        // -------------------------
        // محاسبه تخفیف عمومی
        // -------------------------

        $commonDiscountAmount = 0;
        $commonDiscountPercentage = 0;

        $commonDiscount = CommonDiscount::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if ($commonDiscount && $commonDiscount->percentage > 0 && $totalCartPrice >= $commonDiscount->minimal_order_amount) {

            // محاسبه مبلغ تخفیف عمومی
            $commonDiscountPercentage = $commonDiscount->percentage;
            $commonDiscountAmount = ($totalCartPrice * $commonDiscountPercentage) / 100;

            // چک کردن سقف تخفیف
            if ($commonDiscount->discount_ceiling && $commonDiscountAmount > $commonDiscount->discount_ceiling) {

                $commonDiscountAmount = $commonDiscount->discount_ceiling;
            }
            // جمع سبد خرید
            $totalCartPrice = $totalCartPrice - $commonDiscountAmount;
        }

        return [
            'totalCartPrice' => $totalCartPrice,
            'productPrices' => $productPrices,
            'productDiscounts' => $productDiscounts,
            'commonDiscountAmount' => $commonDiscountAmount,
            'commonDiscountPercentage' => $commonDiscountPercentage,
        ];
    }

    public function updateCart(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $cartItem = CartItem::findOrFail($request->cart_item_id);

            $variant = $cartItem->productVariant;

            $newQuantity = max((int)$request->quantity, 1);
            $oldQuantity = $cartItem->quantity;
            $diff = $newQuantity - $oldQuantity;

            $warehouseVariant = WarehouseVariant::where('product_variant_id', $variant->id)->lockForUpdate()->first();

            if (!$warehouseVariant) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'انبار مربوط به این محصول پیدا نشد.'
                ], 500);
            }

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

            if ($diff > 0) {
                // یعنی تعداد سبد بیشتر شده → رزرو را بیشتر کن
                $warehouseVariant->reserved = $warehouseVariant->reserved + $diff;
                $warehouseVariant->save();
            } elseif ($diff < 0) {
                // یعنی تعداد سبد کمتر شده → رزرو را کم کن
                $warehouseVariant->reserved = $warehouseVariant->reserved - abs($diff);

                // برای احتیاط، نذار منفی بشه
                if ($warehouseVariant->reserved < 0) {
                    $warehouseVariant->reserved = 0;
                }

                $warehouseVariant->save();
            }


            $cartItem->update([
                'quantity' => $newQuantity
            ]);

            // -------------------------
            // محاسبه قیمت 
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

            $totals = $this->calculateCartTotals(Auth::id());


            // -------------------------
            // اعمال کوپن روی total
            // -------------------------
            $couponDiscount = 0;
            if (session('applied_coupon')) {
                $coupon = Coupon::where('code', session('applied_coupon'))
                    ->where('status', 1)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->first();

                if (!$coupon) {
                    session()->forget('applied_coupon');
                } else {

                    // چک کردن درصدی یا عددی بودن تخفیف
                    $couponDiscountCalc = 0;
                    if ($coupon->amount_type == 0) {
                        $couponDiscountCalc = ($totals['totalCartPrice'] * $coupon->amount) / 100;

                        if ($coupon->discount_ceiling && $couponDiscountCalc > $coupon->discount_ceiling) {

                            $couponDiscountCalc = $coupon->discount_ceiling;
                        }
                    } elseif ($coupon->amount_type == 1) {

                        $couponDiscountCalc = $coupon->amount;
                    }

                    // تخفیف نمی‌تواند از کل سبد بیشتر شود
                    $couponDiscountCalc = min($couponDiscountCalc, $totals['totalCartPrice']);
                    $couponDiscount = $couponDiscountCalc;

                    $totals['totalCartPrice'] -= $couponDiscount;
                }
            }


            return response()->json([
                'status' => 'success',

                // آیتم
                'totalItemPrice' => number_format($totalItemPrice, 2),
                'price' => number_format($price, 2),
                'finalPrice' => number_format($finalPrice, 2, '.', ','),
                'discount' => $discount,

                // مقادیر برای آپدیت هدر کارت
                'cart_item_id' => $cartItem->id,
                'new_quantity' => $newQuantity,

                // کل سبد
                'totalCartPrice' => number_format($totals['totalCartPrice'], 2),
                'productPrices' => number_format($totals['productPrices'], 2),
                'productDiscounts' => number_format($totals['productDiscounts'], 2),

                // تخفیف عمومی
                'commonDiscountAmount' => number_format($totals['commonDiscountAmount'], 2),
                'commonDiscountPercentage' => $totals['commonDiscountPercentage'],

                // کوپن تخفیف
                'couponApplied' => $couponDiscount > 0,
                'couponDiscount' => number_format($couponDiscount, 2),
            ]);
        });
    }




    public function coupon(Request $request)
    {
        $data = $request->validate([
            'coupon' => 'required|max:120|min:2'
        ]);

        $coupon = Coupon::where('code', $data['coupon'])
            ->where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$coupon || $data['coupon'] == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'invalid code',
            ]);
        }


        // بررسی استفاده قبلی
        $alreadyUsed = DB::table('coupon_user')
            ->where('user_id', Auth::id())
            ->where('coupon_id', $coupon->id)
            ->exists();

        if ($alreadyUsed) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already used this discount code'
            ]);
        }

        // محاسبه سبد خرید
        $totals = $this->calculateCartTotals(Auth::id());

        $totalCartPrice = $totals['totalCartPrice'];


        // چک کردن عمومی یا خصوصی بودن کوپن
        if ($coupon->type == 1) {
            if ($coupon->user_id != Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This discount code is specific to a specific user'
                ]);
            }
        }


        // چک کردن درصدی یا عددی بودن تخفیف
        if ($coupon->amount_type == 0) {
            $discount = ($totalCartPrice * $coupon->amount) / 100;

            if ($coupon->discount_ceiling && $discount > $coupon->discount_ceiling) {

                $discount = $coupon->discount_ceiling;
            }
        } elseif ($coupon->amount_type == 1) {

            $discount = $coupon->amount;
        }

        // تخفیف نمی‌تواند از کل سبد بیشتر شود
        $discount = min($discount, $totalCartPrice);

        $final_price =  $totalCartPrice - $discount;

        // سشن برای ذخیره کوپن
        session(['applied_coupon' => $coupon->code]);

        return response()->json([
            'status' => 'success',
            'finalPrice' => number_format($final_price, 2, '.', ','),
            'couponDiscountAmount' => number_format($discount, 2),
        ]);
    }
}
