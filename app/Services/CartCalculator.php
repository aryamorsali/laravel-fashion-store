<?php

namespace App\Services;

use App\Models\Market\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartCalculator
{

    public function calculateCartTotals($cartItems, $commonDiscount = null, $couponCode = null)
    {

        ///////////////////////////////////////////////////////////////////////////////////////////////////

        // محاسبات total cart
        $totalCartPrice = 0;
        $productDiscounts = 0;
        $productPrices = 0;
        $commonDiscountAmount = 0;
        $commonDiscountPercentage = null;
        $couponDiscount = 0;



        foreach ($cartItems as $item) {

            $price = $item->productVariant?->price;
            $finalPrice = $price;
            $amazingSaleDiscount = null;

            // تخفیف شگفت انگیز
            $activeAmazingSale =
                $item->productVariant &&
                $item->productVariant->amazingSale &&
                $item->productVariant->amazingSale->is_active &&
                $item->productVariant->amazingSale->start_date <= now() &&
                $item->productVariant->amazingSale->end_date >= now();

            if ($activeAmazingSale) {
                $amazingSaleDiscount = $item->productVariant->amazingSale->percentage;
                $finalPrice = $price - ($price * $amazingSaleDiscount) / 100;
            }

            // مقدار تخفیف کالاها
            if ($activeAmazingSale) {
                $productDiscounts += ($price * $item->quantity * $amazingSaleDiscount) / 100;
            }

            // قیمت کل محصولات بدون تخفیف
            $productPrices += $price * $item->quantity;

            //  قیمت کل نهایی با حسب تخفیف و تعداد
            $totalCartPrice += $item->quantity * $finalPrice;
        }



        // اعمال تخفیف عمومی سایت
        if (
            $commonDiscount &&
            $totalCartPrice >= $commonDiscount->minimal_order_amount
        ) {
            // محاسبه مبلغ تخفیف عمومی
            $commonDiscountPercentage = $commonDiscount->percentage;
            $commonDiscountAmount =
                ($totalCartPrice * $commonDiscountPercentage) / 100;

            // چک کردن سقف تخفیف
            if (
                $commonDiscount->discount_ceiling &&
                $commonDiscountAmount > $commonDiscount->discount_ceiling
            ) {
                $commonDiscountAmount = $commonDiscount->discount_ceiling;
            }
            $totalCartPrice = $totalCartPrice - $commonDiscountAmount;
        }

        // اعمال کوپن (اگه تو session باشه)

        if ($couponCode) {
            $coupon = Coupon::where(
                'code',
                $couponCode,
            )->first();

            if (
                $coupon &&
                $coupon->status == 1 &&
                $coupon->start_date <= now() &&
                $coupon->end_date >= now()
            ) {

                // بررسی استفاده قبلی کاربر از این کوپن
                $alreadyUsed = false;
                if (Auth::check()) {
                    $alreadyUsed = DB::table('coupon_user')
                        ->where('user_id', Auth::id())
                        ->where('coupon_id', $coupon->id)
                        ->exists();
                }

                if (!$alreadyUsed) {
                    // درصدی یا عددی بودن کوپن
                    if ($coupon->amount_type == 0) {
                        $couponDiscount = ($totalCartPrice * $coupon->amount) / 100;
                        if (
                            $coupon->discount_ceiling &&
                            $couponDiscount > $coupon->discount_ceiling
                        ) {
                            $couponDiscount = $coupon->discount_ceiling;
                        }
                    } else {
                        $couponDiscount = $coupon->amount;
                    }
                    $couponDiscount = min($couponDiscount, $totalCartPrice);
                    $totalCartPrice -= $couponDiscount;
                } else {
                    // اگر کوپن قبلا استفاده شده بود آن را از سشن پاک می‌کنیم
                    session()->forget('applied_coupon');
                }
            } else {
                session()->forget('applied_coupon');
            }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////

        return [
            'totalCartPrice' => $totalCartPrice,
            'productDiscounts' => $productDiscounts,
            'productPrices' => $productPrices,
            'commonDiscountAmount' => $commonDiscountAmount,
            'couponDiscount' => $couponDiscount,
            'commonDiscountPercentage' => $commonDiscountPercentage,
        ];
    }
}
