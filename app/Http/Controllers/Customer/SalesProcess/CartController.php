<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\AddToCartRequest;
use App\Models\Market\CartItem;
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
        $cartItems = CartItem::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();


        return view('customer.sales-process.shoping-cart', compact('cartItems'));
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

        return [
            'totalCartPrice' => $totalCartPrice,
            'productPrices' => $productPrices,
            'productDiscounts' => $productDiscounts,
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


            return response()->json([
                'status' => 'success',

                // آیتم
                'totalItemPrice' => number_format($totalItemPrice, 2),
                'price' => number_format($price, 2),
                'finalPrice' => number_format($finalPrice, 2, '.', ','),
                'discount' => $discount,

                // کل سبد
                'totalCartPrice' => number_format($totals['totalCartPrice'], 2),
                'productPrices' => number_format($totals['productPrices'], 2),
                'productDiscounts' => number_format($totals['productDiscounts'], 2),
            ]);
        });
    }


    public function coupon(Request $request)
    {
        $data = $request->validate([
            'coupon' => 'required|max:120|min:2'
        ]);

        $coupon = Coupon::where('code', $data['coupon'])->first();

        if (!$coupon || $data['coupon'] == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'invalid code',
            ]);
        }

        if ($coupon->status != 1 || $coupon->start_date > now() || $coupon->end_date < now()) {
            return response()->json([
                'status' => 'error',
                'message' => 'coupon expired'
            ]);
        }

        // محاسبه سبد خرید
        $totals = $this->calculateCartTotals(Auth::id());

        $totalCartPrice = $totals['totalCartPrice'];

        // چک کردن عمومی یا خصوصی بودن کوپن
        if ($coupon->amount_type == 0)
        {
            $discount = ($totalCartPrice * $coupon->amount) / 100;

            if ($coupon->discount_ceiling) {
                $discount = min($discount, $coupon->discount_ceiling);
            }
        } elseif ($coupon->amount_type == 1)
        {
            if ($coupon->user_id != Auth::id())
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'invalid code'
                ]);
            }
            $discount = min($coupon->amount, $totalCartPrice);
        }

        $final_price = max(0, $totalCartPrice - $discount);

        return response()->json([
            'status' => 'success',
            'finalPrice' => number_format($final_price, 2, '.', ','),
            'couponDiscountAmount' => number_format($discount, 2),
        ]);
    }
}
