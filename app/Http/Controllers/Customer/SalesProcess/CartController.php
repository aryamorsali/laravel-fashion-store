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

    public function shopingCart()
    {
        $result = $this->cartManager->shopingCart();


        $cartItems = $result['cartItems'];
        $commonDiscount = $result['commonDiscount'];
        $totals = $result['totals'];
        return view('customer.sales-process.shoping-cart', compact('cartItems', 'commonDiscount', 'totals'));
    }



    public function addToCart(AddToCartRequest $request)
    {
        $data = $request->validated();

        $this->cartManager->addToCart($data);

        return redirect()->back()->with(
            'toast-success',
            'Product successfully added to your cart !'
        );
    }

    public function removeFromCart(CartItem $cartItem)
    {
        try {
            $this->cartManager->removeFromCart($cartItem);
        } catch (\DomainException $e) {
            return back()->with(
                'toast-error',
                $e->getMessage()
            );
        }

        return back();
    }

    public function updateCart(Request $request)
    {
        $data = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|between:1,10'
        ]);

        return response()->json(
            $this->cartManager->updateCart($data)
        );
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
