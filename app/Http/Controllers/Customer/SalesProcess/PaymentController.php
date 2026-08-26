<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Services\Payment\PaymentService;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Order;
use App\Models\Market\Payment;
use App\Services\CartCalculator;
use App\Models\Market\Address;
use App\Models\Market\CartItem;
use App\Models\Market\Coupon;
use App\Models\Market\CouponUser;
use App\Models\Market\Delivery;
use App\Models\Market\InventoryAllocation;
use App\Models\Market\WarehouseTransaction;
use App\Models\Market\WarehouseVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class PaymentController extends Controller
{
    public function payment(Request $request, CartCalculator $cartCalculator, PaymentService $paymentService)
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


        // create order
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

        $result = DB::transaction(function () use ($data, $cartItems, $userId) {
            $order = Order::where('user_id', $userId)
                ->whereIn('order_status', ['not_checked', 'awaiting_confirmation'])
                ->whereIn('payment_status', ['unpaid', 'failed'])
                ->lockForUpdate()
                ->latest()
                ->first();

            if ($order) {
                $order->load('orderItems.allocations');

                // آزاد سازی تخصیص‌ های قبلی (برگرداندن به حالت اختصاص نیافته در سبد خرید)
                foreach ($order->orderItems as $orderItem) {
                    InventoryAllocation::query()
                        ->where('order_item_id', $orderItem->id)
                        ->update(['order_item_id' => null]);
                }

                // حذف فیزیکی order item
                $order->orderItems()->forceDelete();

                // سفارش قبلی ناتمام وجود داره آپدیتش کن
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

                // create order item
                $orderItem = $order->orderItems()->create([
                    'product_variant_id' => $cartItem->productVariant->id,
                    'amazing_sale_id' => $cartItem->productVariant->activeAmazingSale?->id,
                    'product_snapshot' => $productSnapshot,
                    'amazing_sale_snapshot' => $amazingSaleSnapshot,
                    'amazing_sale_discount_amount' => $amazingSaleDiscountAmount,
                    'quantity' => $cartItem->quantity,
                    'final_product_price' => $cartItem->productVariant->price - $amazingSaleDiscountAmount,
                    'final_total_price' => $cartItem->quantity * ($cartItem->productVariant->price - $amazingSaleDiscountAmount),
                ]);


                // allocation آپدیت
                $allocations = InventoryAllocation::query()
                    ->where('cart_item_id', $cartItem->id)
                    ->whereNull('order_item_id')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                foreach ($allocations as $allocation) {
                    $allocation->update([
                        'order_item_id' => $orderItem->id,
                    ]);
                }
            }

            // create payment

            $payment = Payment::where('order_id', $order->id)
                ->whereIn('status', ['unpaid', 'failed'])
                ->lockForUpdate()
                ->latest()
                ->first();

            if ($payment) {
                $payment->update([
                    'amount' => $order->order_final_amount,
                    'gateway' => 'zarinpal',
                    'status' => 'unpaid',
                    'transaction_id' => null,
                    'paid_at' => null,
                ]);
            } else {
                $payment = Payment::create([
                    'user_id' => $userId,
                    'order_id' => $order->id,
                    'amount' => $order->order_final_amount,
                    'gateway' => 'zarinpal',
                    'status' => 'unpaid',
                ]);
            }

            return [
                'order' => $order,
                'payment' => $payment,
            ];
        });
        return $paymentService->zarinpal($result['order']->order_final_amount, $result['order'], $result['payment']);
    }


    public function paymentCallBack(Request $request, Order $order, Payment $payment, PaymentService $paymentService)
    {

        if ($payment->order_id !== $order->id) {
            abort(404, 'Payment information does not match the order.');
        }

        $result = $paymentService->zarinpalVerify($request, $payment);

        // اگر پرداخت موفقیت آمیز بود

        return DB::transaction(function () use ($order, $payment, $result) {

            $order = Order::lockForUpdate()->findOrFail($order->id);
            $payment = Payment::lockForUpdate()->findOrFail($payment->id);

            // already_paid
            if ($order->order_status === 'confirmed' && $order->payment_status === 'paid' && $payment->status === 'paid') {
                // قبلا سفارش پردازش شده است
                return redirect()->back()->with(
                    'toast-success',
                    $result['message']
                );
            }

            $order->load('orderItems.allocations.cartItem');

            // ----------------------------------
            // اگر پرداخت موفق بود
            // ----------------------------------
            if ($result['status'] === 'success') {

                // بروزرسانی تراکنش پرداخت
                $payment->update([
                    'status' => 'paid',
                    'second_response' => [
                        'reference_id' => $result['reference_id'],
                        'driver'       => $result['driver'],
                        'amount'       => (int) $payment->amount,
                        'details'      => $result['details'],
                    ],
                    'paid_at' => now(),
                ]);

                foreach ($order->orderItems as $orderItem) {

                    // کم کردن موجودی و ثبت تراکنش
                    foreach ($orderItem->allocations as $allocation) {
                        $warehouseVariant = WarehouseVariant::query()
                            ->lockForUpdate()
                            ->findOrFail($allocation->warehouse_variant_id);

                        // درست کردن موجودی انبار و واریانت
                        $warehouseVariant->stock -= $allocation->quantity;
                        $warehouseVariant->reserved = max(0, $warehouseVariant->reserved - $allocation->quantity);
                        $warehouseVariant->sold += $allocation->quantity;
                        $warehouseVariant->save();

                        // ثبت تراکنش
                        WarehouseTransaction::create([
                            'warehouse_id' => $warehouseVariant->warehouse_id,
                            'product_variant_id' => $orderItem->product_variant_id,
                            'type' => 'out',
                            'quantity' => $allocation->quantity,
                            'unit_price' => $orderItem->final_product_price,
                        ]);
                    }
                    // حذف تخصیص داده شده ها و آیتم سبد کاربر
                    $cartItemIds = $orderItem->allocations->pluck('cart_item_id')->unique()->filter();

                    $orderItem->allocations()->delete();

                    if ($cartItemIds->isNotEmpty()) {
                        CartItem::query()
                            ->whereIn('id', $cartItemIds)
                            ->delete();
                    }
                }

                // اگر کاربر از کوپن استفاده کرده بود
                if ($order->coupon_id) {
                    CouponUser::create([
                        'user_id' => $order->user_id,
                        'coupon_id' => $order->coupon_id,
                        'order_id' => $order->id,
                        'used_at' => now(),
                    ]);
                }

                $order->update([
                    'order_status' => 'confirmed',
                    'payment_status' => 'paid',
                ]);

                return redirect()->route('customer.home')->with(
                    'toast-success',
                    $result['message']
                );
            }

            
            // ----------------------------------
            // اگر پرداخت ناموفق یا کنسل شده بود
            // ---------------------------------

            // invalid_authority
            $paymentStatus = null;
            $orderStatus = null;

            if ($result['status'] === 'invalid_authority' || $result['status'] === 'mismatched_authority' || $result['status'] === 'verification_failed') {

                $paymentStatus = 'failed';
                $orderStatus   = 'awaiting_confirmation';
            }

            if ($result['status'] === 'canceled_by_user') {

                $paymentStatus =  'unpaid';
                $orderStatus   =  'canceled';
            }

            $payment->update([
                'status' => $paymentStatus ?? 'failed',
                'second_response' => $result['payload'],
            ]);

            $order->update([
                'payment_status' => $paymentStatus ?? 'failed',
                'order_status'   => $orderStatus ?? 'awaiting_confirmation',
            ]);

            // آزاد کردن سبد کاربر برای حذف
            foreach ($order->orderItems as $orderItem) {
                foreach ($orderItem->allocations as $allocation) {
                    $allocation->update([
                        'order_item_id' => null
                    ]);
                }
            }
            return redirect()->route('customer.home')->with('toast-error', $result['message']);
        });
    }
}
