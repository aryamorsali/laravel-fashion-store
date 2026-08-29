<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Services\Payment\PaymentService;
use App\Services\PaymentService as payService;
use App\Models\Market\Order;
use App\Models\Market\Payment;
use App\Models\Market\CartItem;
use App\Models\Market\CouponUser;
use App\Models\Market\WarehouseTransaction;
use App\Models\Market\WarehouseVariant;
use App\Services\CartCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $paymentService, $paymentCalculationService;
    public function __construct(PaymentService $paymentService, payService $paymentCalculationService)
    {
        $this->paymentService = $paymentService;
        $this->paymentCalculationService = $paymentCalculationService;
    }


    public function payment(Request $request, CartCalculator $cartCalculator)
    {
        $inputs = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'delivery_id' => 'required|exists:deliveries,id',
        ]);

        $result = $this->paymentCalculationService->payment($inputs, $cartCalculator);

        return $this->paymentService->zarinpal($result['order']->order_final_amount, $result['order'], $result['payment']);
    }


    public function paymentCallBack(Request $request, Order $order, Payment $payment, PaymentService $paymentService)
    {

        if ($payment->order_id !== $order->id) {
            abort(404, 'اطلاعات پرداخت با سفارش مطابقت ندارد.');
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
