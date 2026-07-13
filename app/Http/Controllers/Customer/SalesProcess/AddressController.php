<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile\StoreAddressRequest;
use App\Http\Requests\Customer\Profile\UpdateAddressRequest;
use App\Models\Market\Address;
use App\Models\Market\CartItem;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use App\Models\Market\Delivery;
use App\Models\Market\Order;
use App\Models\Market\Province;
use App\Services\CartCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function addressAndDelivery(CartCalculator $cartCalculator)
    {
        $cartItems = CartItem::where('user_id', Auth::user()->id)
            ->with('productVariant.amazingSale')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back();
        }

        $commonDiscount = CommonDiscount::where('status', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

        $provinces = Province::with('cities')->get();

        $addresses = Auth::user()->addresses;
        $deliveries = Delivery::where('status', 1)->get();

        $totals = $cartCalculator->calculateCartTotals($cartItems, $commonDiscount, session('applied_coupon'));


        return view('customer.sales-process.address-and-delivery', compact(
            'cartItems',
            'commonDiscount',
            'totals',
            'provinces',
            'addresses',
            'deliveries'
        ));
    }

    public function getCities(Province $province)
    {
        return response()->json($province->cities()->select('id', 'name')->get());
    }


    public function storeAddress(StoreAddressRequest $request)
    {
        $inputs = $request->validated();

        Address::create([
            'user_id' => Auth::user()->id,
            'recipient_name' => $inputs['recipient_name'],
            'city_id' => $inputs['city_id'],
            'province_id' => $inputs['province_id'],
            'address' => $inputs['address'],
            'postal_code' => $inputs['postal_code'],
            'no' => $inputs['no'],
            'unit' => $inputs['unit'],
            'mobile' => $inputs['mobile'],
        ]);

        return redirect()->back()->with(
            'toast-success',
            'Address created successfuly.'
        );
    }

    public function updateAddress(UpdateAddressRequest $request, Address $address)
    {
        $inputs = $request->validated();

        $address->update($inputs);

        return redirect()->back()->with(
            'toast-success',
            'Address updated successfuly.'
        );
    }

    public function chooseAddressAndDelivery(Request $request, CartCalculator $cartCalculator)
    {
        $inputs = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'delivery_id' => 'required|exists:deliveries,id',
        ]);

        $userId = Auth::user()->id;

        $address = Address::with(['province', 'city'])->where('user_id', $userId)->findOrFail($inputs['address_id']);

        $delivery = Delivery::findOrFail($inputs['delivery_id']);
        // محاسبه سبد
        $cartItems = CartItem::where('user_id', $userId)->with([
            'productVariant',
            'productVariant.product',
            'productVariant.amazingSale',
        ])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->back();
        }

        $commonDiscount = CommonDiscount::where('status', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();
        $coupon = null;
        if (session()->has('applied_coupon')) {
            $coupon = Coupon::where('code', session('applied_coupon'))->first();
        }
        $totals = $cartCalculator->calculateCartTotals($cartItems, $commonDiscount, $coupon?->code);

        $addressSnapshot = [
            'province' => $address->province->name,
            'city' => $address->city->name,
            'address' => $address->address,
            'postal_code' => $address->postal_code,
            'receiver' => $address->recipient_name,
            'mobile' => $address->mobile,
            'unit' => $address->unit,
            'no' => $address->no,
        ];

        $deliverySnapshot = [
            'name' => $delivery->name,
            'delivery_cost' => $delivery->delivery_cost,
            'delivery_days' => $delivery->delivery_days,
        ];

        // ممکن است کوپن در سرویس نامعتبر، منقضی یا قبلا استفاده شده باشد
        $couponApplied = $coupon && $totals['couponDiscount'] > 0;

        if (!$couponApplied) {
            $coupon = null;
            session()->forget('applied_coupon');
        }

        $couponSnapshot = $coupon ? [
            'code' => $coupon->code,
            'amount' => $coupon->amount,
            'amount_type' => $coupon->amount_type,     // درصدی یا عددی
            'discount_ceiling' => $coupon->discount_ceiling,
            'type' => $coupon->type,    // عمومی یا خصوصی
            'user_id' => $coupon->user_id,
            'status' => $coupon->status,
            'start_date' => $coupon->start_date,
            'end_date' => $coupon->end_date,
        ] : null;

        $commonDiscountSnapshot = $commonDiscount ? [
            'title' => $commonDiscount->title,
            'status' => $commonDiscount->status,
            'start_date' => $commonDiscount->start_date,
            'end_date' => $commonDiscount->end_date,
            'percentage' => $commonDiscount->percentage,
            'discount_ceiling' => $commonDiscount->discount_ceiling,
            'minimal_order_amount' => $commonDiscount->minimal_order_amount,
        ] : null;

        //  order data
        $data = [
            'user_id' => $userId,
            'address_id' => $inputs['address_id'],
            'address_snapshot' => $addressSnapshot,
            'order_final_amount' => $totals['totalCartPrice'] + $delivery->delivery_cost,
            'order_total_products_discount_amount' => $totals['productDiscounts'],
            'order_discount_amount' => $totals['productDiscounts'] + $totals['commonDiscountAmount'] + $totals['couponDiscount'],
            'delivery_id' => $inputs['delivery_id'],
            'delivery_snapshot' => $deliverySnapshot,
            'delivery_amount' => $delivery->delivery_cost,
            'delivery_date' => $delivery->delivery_days ? now()->addDays($delivery->delivery_days) : null,
                
            'coupon_id' => $coupon ? $coupon->id : null,
            'coupon_snapshot' => $couponSnapshot,
            'order_coupon_discount_amount' => $totals['couponDiscount'],
            'common_discount_id' => $totals['commonDiscountAmount'] > 0 ? $commonDiscount?->id : null,
            'common_discount_snapshot' => $commonDiscountSnapshot,
            'order_common_discount_amount' => $totals['commonDiscountAmount'],
        ];

        DB::transaction(function () use ($data, $cartItems, $userId) {
            $order = Order::where('user_id', $userId)
                ->whereIn('order_status', ['not_checked', 'awaiting_confirmation'])
                ->whereIn('payment_status', ['unpaid', 'failed'])
                ->latest()
                ->first();

            if ($order) {
                // سفارش قبلی ناتمام وجود داره، آپدیتش کن
                $order->orderItems()->delete();
                $order->update($data);
            } else {
                // سفارش جدید بساز
                $order = Order::create($data);
            }

            // ساختن order items
            foreach ($cartItems as $cartItem) {

                $productSnapshot = [
                    'has_color' => $cartItem->productVariant->product->has_color,
                    'has_size' => $cartItem->productVariant->product->has_size,
                    'name' => $cartItem->productVariant->product->name,
                    'slug' => $cartItem->productVariant->product->slug,
                    'description' => $cartItem->productVariant->product->description ?? null,
                    'image' => $cartItem->productVariant->product->image,
                    'base_price' => $cartItem->productVariant->product->base_price,
                    'brand_id' => $cartItem->productVariant->product->brand_id ?? null,
                    'category_id' => $cartItem->productVariant->product->category_id ?? null,
                    'status' => $cartItem->productVariant->product->status,
                    'published_at' => $cartItem->productVariant->product->published_at ?? null,
                    'created_at' => $cartItem->productVariant->product->created_at ?? null,
                    'updated_at' => $cartItem->productVariant->product->updated_at ?? null,
                    'deleted_at' => $cartItem->productVariant->product->deleted_at ?? null,
                ];

                $amazingSaleSnapshot = $cartItem->productVariant->activeAmazingSale ?  [
                    'product_variant_id' => $cartItem->productVariant->id,
                    'percentage' => $cartItem->productVariant->activeAmazingSale->percentage,
                    'start_date' => $cartItem->productVariant->activeAmazingSale->start_date,
                    'end_date' => $cartItem->productVariant->activeAmazingSale->end_date,
                    'is_active' => $cartItem->productVariant->activeAmazingSale->is_active,
                    'created_at' => $cartItem->productVariant->activeAmazingSale->created_at ?? null,
                    'updated_at' => $cartItem->productVariant->activeAmazingSale->updated_at ?? null,
                    'deleted_at' => $cartItem->productVariant->activeAmazingSale->deleted_at ?? null,
                ] : null;

                $amazingSaleDiscountAmount = $cartItem->productVariant->activeAmazingSale
                    ? ($cartItem->productVariant->price * $cartItem->productVariant->activeAmazingSale->percentage) / 100 : 0;

                $order->orderItems()->create([
                    'product_variant_id' => $cartItem->productVariant->id,
                    'amazing_sale_id' => $cartItem->productVariant->activeAmazingSale?->id,
                    'product_snapshot' => $productSnapshot,
                    'amazing_sale_snapshot' => $amazingSaleSnapshot,
                    'amazing_sale_discount_amount' => $amazingSaleDiscountAmount,
                    'quantity' => $cartItem->quantity,
                    'final_product_price' => $cartItem->productVariant->price - $amazingSaleDiscountAmount,
                    'final_total_price' => $cartItem->quantity * ($cartItem->productVariant->price - $amazingSaleDiscountAmount),
                ]);
            }
        });

        dd("end proccess");
        return redirect()->route('customer.sales-process.payment');
    }
}
