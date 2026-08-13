<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\AddToCartRequest;
use App\Models\Market\CartItem;
use App\Services\CartManager;
use Illuminate\Http\Request;

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

        $result = $this->cartManager->updateCart($data, session('applied_coupon'));

        return response()->json([
            'status' => 'success',

            // آیتم
            'totalItemPrice' => number_format($result['totalItemPrice'], 2),
            'price' => number_format($result['price'], 2),
            'finalPrice' => number_format($result['finalPrice'], 2),
            'discount' => $result['discount'],

            // هدر سبد
            'cart_item_id' => $result['cart_item_id'],
            'new_quantity' => $result['new_quantity'],
            'totalProductsQuantity' => $result['totalProductsQuantity'],

            // جمع سبد
            'totalCartPrice' => number_format($result['totalCartPrice'], 2),
            'productPrices' => number_format($result['productPrices'], 2),
            'productDiscounts' => number_format($result['productDiscounts'], 2),

            // تخفیف عمومی
            'commonDiscountAmount' => number_format(
                $result['commonDiscountAmount'],
                2
            ),
            'commonDiscountPercentage' => $result['commonDiscountPercentage'],

            // کوپن
            'couponApplied' => $result['couponApplied'],
            'couponDiscount' => number_format($result['couponDiscount'], 2),
        ]);
    }


    public function coupon(Request $request)
    {
        $data = $request->validate([
            'coupon' => 'required|max:120|min:2'
        ]);

        $result = $this->cartManager->applyCoupon($data);
        // سشن برای ذخیره کوپن
        session(['applied_coupon' => $result['coupon']]);

        return response()->json([
            'status' => 'success',
            'finalPrice' => number_format($result['finalPrice'], 2),
            'couponDiscountAmount' => number_format($result['couponDiscountAmount'], 2),
        ]);
    }
}
