<?php

namespace App\Services;

use App\Exceptions\EmptyCartException;
use App\Models\Market\CartItem;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use App\Models\Market\Delivery;
use App\Models\Market\Province;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AddressService
{
    protected $cartCalculator;

    public function __construct(CartCalculator $cartCalculator)
    {
        $this->cartCalculator = $cartCalculator;
    }


    public function addressAndDelivery($couponCode = null)
    {
        $cartItems = CartItem::where('user_id', Auth::user()->id)
            ->with('productVariant.amazingSale')
            ->get();

        if ($cartItems->isEmpty()) {
            throw new EmptyCartException();
        }


        $commonDiscount = CommonDiscount::where('status', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

        $provinces = Province::with('cities')->get();

        $addresses = Auth::user()->addresses()->with(['province', 'city'])->get();

        $deliveries = Delivery::where('status', 1)->get();


        $code = $couponCode ?? session('applied_coupon');

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
                // اگر درخواست وب بود
                if ($couponCode === null) {
                    session()->forget('applied_coupon');
                }

                throw ValidationException::withMessages([
                    'coupon' => 'The coupon code is not valid for your account.',
                ]);
            }
        }

        // -------------------------
        // محاسبه کل سبد خرید
        // -------------------------

        $totals = $this->cartCalculator->calculateCartTotals($cartItems, $commonDiscount, $coupon ? $coupon->code : null);


        return [
            'cartItems' => $cartItems,
            'commonDiscount' => $commonDiscount,
            'totals' => $totals,
            'provinces' => $provinces,
            'addresses' => $addresses,
            'deliveries' => $deliveries,
        ];
    }
}
