<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Payment\PaymentCallbackRequest;
use App\Http\Requests\Customer\Payment\PaymentRequest;
use App\Http\Services\Payment\PaymentService;
use App\Models\Market\Order;
use App\Models\Market\Payment;
use App\Services\CartCalculator;
use App\Services\PaymentCalculationService;

class PaymentController extends Controller
{
    protected $paymentService, $paymentCalculationService;
    public function __construct(PaymentService $paymentService, PaymentCalculationService $paymentCalculationService)
    {
        $this->paymentService = $paymentService;
        $this->paymentCalculationService = $paymentCalculationService;
    }


    public function payment(PaymentRequest $request, CartCalculator $cartCalculator)
    {
        $inputs = $request->validated();

        $result = $this->paymentCalculationService->payment($inputs, $cartCalculator);

        return $this->paymentService->zarinpal($result['order']->order_final_amount, $result['order'], $result['payment']);
    }


    public function paymentCallBack(PaymentCallbackRequest $request, Order $order, Payment $payment)
    {
        return $this->paymentCalculationService->paymentCallBack($request, $order, $payment);
    }

}
