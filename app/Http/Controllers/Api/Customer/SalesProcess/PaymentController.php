<?php

namespace App\Http\Controllers\Api\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Payment\PaymentCallbackRequest;
use App\Http\Requests\Api\Payment\PaymentRequest;
use App\Http\Resources\OrderResource;
use App\Services\CartCalculator;
use App\Http\Services\Payment\PaymentService;
use App\Models\Market\Order;
use App\Models\Market\Payment;
use App\Services\PaymentCalculationService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Payment',
    description: 'Payment, Payment callback'
)]

class PaymentController extends Controller
{
    protected $paymentService, $paymentCalculationService;

    public function __construct(PaymentService $paymentService, PaymentCalculationService $paymentCalculationService)
    {
        $this->paymentService = $paymentService;
        $this->paymentCalculationService = $paymentCalculationService;
    }



    #[OA\Post(
        path: '/api/payment',
        summary: 'Final order registration and receipt of payment gateway link',
        description: 'This endpoint processes the users shopping cart, applies the discount coupon (if any), saves the order along with a snapshot of the information, and returns a link to connect to the payment gateway (ZarinPal).',
        security: [['sanctum' => []]],
        tags: ['Payment'],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Information required for final order registration including address, shipping method, and optional discount code',
            content: new OA\JsonContent(
                required: ['address_id', 'delivery_id'],
                properties: [
                    new OA\Property(
                        property: 'address_id',
                        type: 'integer',
                        description: 'Users order delivery address ID',
                        example: 12
                    ),
                    new OA\Property(
                        property: 'delivery_id',
                        type: 'integer',
                        description: 'Selected delivery method ID',
                        example: 2
                    ),
                    new OA\Property(
                        property: 'coupon',
                        type: 'string',
                        nullable: true,
                        description: 'Discount coupon code (optional)',
                        example: 'testy'
                    )
                ]
            )
        ),

        responses: [
            new OA\Response(
                response: 200,
                description: 'The order was successfully placed and the payment gateway link was issued.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'The order has been registered and is ready to connect to the portal.'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'payment_url',
                                    type: 'string',
                                    format: 'uri',
                                    example: 'https://sandbox.zarinpal.com/pg/StartPay/S000000000000000000000000000002oo3lp'
                                ),
                                new OA\Property(
                                    property: 'order',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 37),
                                        new OA\Property(
                                            property: 'status',
                                            type: 'object',
                                            properties: [
                                                new OA\Property(property: 'order_status', type: 'string', example: 'not_checked'),
                                                new OA\Property(property: 'payment_status', type: 'string', example: 'unpaid'),
                                                new OA\Property(property: 'delivery_status', type: 'string', example: 'sending')
                                            ]
                                        ),
                                        new OA\Property(
                                            property: 'pricing',
                                            type: 'object',
                                            properties: [
                                                new OA\Property(property: 'final_amount', type: 'number', example: 105),
                                                new OA\Property(property: 'total_products_discount', type: 'number', example: 2),
                                                new OA\Property(property: 'total_discount', type: 'number', example: 25),
                                                new OA\Property(property: 'coupon_discount', type: 'number', example: 10),
                                                new OA\Property(property: 'common_discount', type: 'number', example: 12),
                                                new OA\Property(property: 'delivery_amount', type: 'number', example: 7)
                                            ]
                                        ),
                                        new OA\Property(
                                            property: 'snapshots',
                                            type: 'object',
                                            properties: [
                                                new OA\Property(
                                                    property: 'address',
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'province', type: 'string', example: 'Fars'),
                                                        new OA\Property(property: 'city', type: 'string', example: 'Jahrom'),
                                                        new OA\Property(property: 'address', type: 'string', example: 'test location'),
                                                        new OA\Property(property: 'postal_code', type: 'string', example: '1234567890'),
                                                        new OA\Property(property: 'receiver', type: 'string', example: 'abdollah'),
                                                        new OA\Property(property: 'mobile', type: 'string', example: '98765432100'),
                                                        new OA\Property(property: 'unit', type: 'string', example: '3'),
                                                        new OA\Property(property: 'no', type: 'string', example: '3')
                                                    ]
                                                ),
                                                new OA\Property(
                                                    property: 'delivery',
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'name', type: 'string', example: 'motor peyck'),
                                                        new OA\Property(property: 'delivery_cost', type: 'string', example: '7.000'),
                                                        new OA\Property(property: 'delivery_days', type: 'integer', example: 5)
                                                    ]
                                                ),
                                                new OA\Property(
                                                    property: 'coupon',
                                                    type: 'object',
                                                    nullable: true,
                                                    properties: [
                                                        new OA\Property(property: 'code', type: 'string', example: 'testy'),
                                                        new OA\Property(property: 'amount', type: 'string', example: '10.00'),
                                                        new OA\Property(property: 'amount_type', type: 'integer', example: 0),
                                                        new OA\Property(property: 'discount_ceiling', type: 'number', nullable: true, example: null),
                                                        new OA\Property(property: 'type', type: 'integer', example: 0),
                                                        new OA\Property(property: 'user_id', type: 'integer', nullable: true, example: null),
                                                        new OA\Property(property: 'status', type: 'integer', example: 1),
                                                        new OA\Property(property: 'start_date', type: 'string', example: '2026-08-09 12:00:00'),
                                                        new OA\Property(property: 'end_date', type: 'string', example: '2026-09-30 12:00:00')
                                                    ]
                                                ),
                                                new OA\Property(
                                                    property: 'common_discount',
                                                    type: 'object',
                                                    nullable: true,
                                                    properties: [
                                                        new OA\Property(property: 'title', type: 'string', example: 'روز کارگر'),
                                                        new OA\Property(property: 'status', type: 'integer', example: 1),
                                                        new OA\Property(property: 'start_date', type: 'string', example: '2025-11-02 16:00:00'),
                                                        new OA\Property(property: 'end_date', type: 'string', example: '2026-08-31 14:00:00'),
                                                        new OA\Property(property: 'percentage', type: 'integer', example: 10),
                                                        new OA\Property(property: 'discount_ceiling', type: 'number', example: 15),
                                                        new OA\Property(property: 'minimal_order_amount', type: 'number', example: 100)
                                                    ]
                                                )
                                            ]
                                        ),
                                        new OA\Property(property: 'delivery_date', type: 'string', format: 'date-time', example: '2026-09-03T20:06:53.641931Z'),
                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-08-29T19:48:56.000000Z'),
                                        new OA\Property(
                                            property: 'order_items',
                                            type: 'array',
                                            items: new OA\Items(
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 168),
                                                    new OA\Property(property: 'order_id', type: 'integer', example: 37),
                                                    new OA\Property(property: 'product_variant_id', type: 'integer', example: 116),
                                                    new OA\Property(property: 'amazing_sale_id', type: 'integer', nullable: true, example: null),
                                                    new OA\Property(property: 'quantity', type: 'integer', example: 1),
                                                    new OA\Property(property: 'product_snapshot', type: 'object'),
                                                    new OA\Property(property: 'amazing_sale_snapshot', type: 'object', nullable: true),
                                                    new OA\Property(property: 'amazing_sale_discount_amount', type: 'number', example: 0),
                                                    new OA\Property(property: 'final_product_price', type: 'number', example: 100),
                                                    new OA\Property(property: 'final_total_price', type: 'number', example: 100)
                                                ]
                                            )
                                        )
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/401ResponseSchema'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/422ResponseSchema')
            ),

            new OA\Response(
                response: 429,
                description: 'Too many requests',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/429ResponseSchema'
                )
            ),
        ]
    )]

    public function payment(PaymentRequest $request, CartCalculator $cartCalculator)
    {
        $inputs = $request->validated();

        $result = $this->paymentCalculationService->payment($inputs, $cartCalculator, $inputs['coupon'] ?? null);

        // ارسال پاسخ JSON شامل لینک پرداخت به 
        $paymentUrl = $this->paymentService->getZarinpalUrl($result['order']->order_final_amount, $result['order'], $result['payment']);

        return response()->json([
            'status' => true,
            'message' => 'The order has been registered and is ready to connect to the portal.',
            'data' => [
                'payment_url' => $paymentUrl,
                'order' => new OrderResource($result['order']),
            ]
        ]);
    }

    #[OA\Get(
        path: '/api/payment-callback/{order}/{payment}',
        summary: 'Handle payment gateway callback',
        description: 'Verify payment transaction from Zarinpal gateway, finalize order status, allocate warehouse stock, and return full order details',
        tags: ['Payment'],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                description: 'The ID of the order',
                schema: new OA\Schema(type: 'integer', example: 39)
            ),
            new OA\Parameter(
                name: 'payment',
                in: 'path',
                required: true,
                description: 'The ID of the payment transaction',
                schema: new OA\Schema(type: 'integer', example: 62)
            ),
            new OA\Parameter(
                name: 'Authority',
                in: 'query',
                required: true,
                description: 'Payment gateway transaction authority code',
                schema: new OA\Schema(type: 'string', example: 'S00000000000000000000000000000wrg62y')
            ),
            new OA\Parameter(
                name: 'Status',
                in: 'query',
                required: true,
                description: 'Gateway callback status (OK for successful payment, NOK for canceled or failed)',
                schema: new OA\Schema(type: 'string', enum: ['OK', 'NOK'], example: 'OK')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Payment verified successfully, already processed, or canceled by user',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'This order has already been successfully paid and registered.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'order',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 39),
                                        new OA\Property(
                                            property: 'status',
                                            type: 'object',
                                            properties: [
                                                new OA\Property(property: 'order_status', type: 'string', example: 'confirmed'),
                                                new OA\Property(property: 'payment_status', type: 'string', example: 'paid'),
                                                new OA\Property(property: 'delivery_status', type: 'string', example: 'sending'),
                                            ]
                                        ),
                                        new OA\Property(
                                            property: 'pricing',
                                            type: 'object',
                                            properties: [
                                                new OA\Property(property: 'final_amount', type: 'number', example: 128),
                                                new OA\Property(property: 'total_products_discount', type: 'number', example: 0),
                                                new OA\Property(property: 'total_discount', type: 'number', example: 28),
                                                new OA\Property(property: 'coupon_discount', type: 'number', example: 13),
                                                new OA\Property(property: 'common_discount', type: 'number', example: 15),
                                                new OA\Property(property: 'delivery_amount', type: 'number', example: 7),
                                            ]
                                        ),
                                        new OA\Property(
                                            property: 'snapshots',
                                            type: 'object',
                                            properties: [
                                                new OA\Property(
                                                    property: 'address',
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'province', type: 'string', example: 'Isfahan'),
                                                        new OA\Property(property: 'city', type: 'string', example: 'Isfahan'),
                                                        new OA\Property(property: 'address', type: 'string', example: 'tehran valiasr street'),
                                                        new OA\Property(property: 'postal_code', type: 'string', example: '1234567890'),
                                                        new OA\Property(property: 'receiver', type: 'string', example: 'abbas nasiri'),
                                                        new OA\Property(property: 'mobile', type: 'string', example: '09121234567'),
                                                        new OA\Property(property: 'unit', type: 'string', example: '3'),
                                                        new OA\Property(property: 'no', type: 'string', example: '12'),
                                                    ]
                                                ),
                                                new OA\Property(
                                                    property: 'delivery',
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'name', type: 'string', example: 'motor peyck'),
                                                        new OA\Property(property: 'delivery_cost', type: 'string', example: '7.000'),
                                                        new OA\Property(property: 'delivery_days', type: 'integer', example: 5),
                                                    ]
                                                ),
                                                new OA\Property(
                                                    property: 'coupon',
                                                    type: 'object',
                                                    nullable: true,
                                                    properties: [
                                                        new OA\Property(property: 'code', type: 'string', example: 'testy'),
                                                        new OA\Property(property: 'amount', type: 'string', example: '10.00'),
                                                        new OA\Property(property: 'amount_type', type: 'integer', example: 0),
                                                        new OA\Property(property: 'discount_ceiling', type: 'number', nullable: true, example: null),
                                                        new OA\Property(property: 'type', type: 'integer', example: 0),
                                                        new OA\Property(property: 'user_id', type: 'integer', nullable: true, example: null),
                                                        new OA\Property(property: 'status', type: 'integer', example: 1),
                                                        new OA\Property(property: 'start_date', type: 'string', example: '2026-08-09 12:00:00'),
                                                        new OA\Property(property: 'end_date', type: 'string', example: '2026-09-30 12:00:00'),
                                                    ]
                                                ),
                                                new OA\Property(
                                                    property: 'common_discount',
                                                    type: 'object',
                                                    nullable: true,
                                                    properties: [
                                                        new OA\Property(property: 'title', type: 'string', example: 'روز کارگر'),
                                                        new OA\Property(property: 'status', type: 'integer', example: 1),
                                                        new OA\Property(property: 'start_date', type: 'string', example: '2025-11-02 16:00:00'),
                                                        new OA\Property(property: 'end_date', type: 'string', example: '2026-08-31 14:00:00'),
                                                        new OA\Property(property: 'percentage', type: 'integer', example: 10),
                                                        new OA\Property(property: 'discount_ceiling', type: 'number', example: 15),
                                                        new OA\Property(property: 'minimal_order_amount', type: 'number', example: 100),
                                                    ]
                                                ),
                                            ]
                                        ),
                                        new OA\Property(property: 'delivery_date', type: 'string', example: '2026-09-04 22:47:14'),
                                        new OA\Property(property: 'created_at', type: 'string', example: '2026-08-30T19:17:14.000000Z'),
                                        new OA\Property(
                                            property: 'order_items',
                                            type: 'array',
                                            items: new OA\Items(
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 168),
                                                    new OA\Property(property: 'order_id', type: 'integer', example: 37),
                                                    new OA\Property(property: 'product_variant_id', type: 'integer', example: 116),
                                                    new OA\Property(property: 'amazing_sale_id', type: 'integer', nullable: true, example: null),
                                                    new OA\Property(property: 'quantity', type: 'integer', example: 1),
                                                    new OA\Property(property: 'product_snapshot', type: 'object'),
                                                    new OA\Property(property: 'amazing_sale_snapshot', type: 'object', nullable: true),
                                                    new OA\Property(property: 'amazing_sale_discount_amount', type: 'number', example: 0),
                                                    new OA\Property(property: 'final_product_price', type: 'number', example: 100),
                                                    new OA\Property(property: 'final_total_price', type: 'number', example: 100)
                                                ]
                                            )
                                        )
                                    ]
                                ),
                                new OA\Property(property: 'transaction_id', type: 'string', example: 'S00000000000000000000000000000wrg62y'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Payment verification failed or invalid authority code',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'verification_failed'),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment confirmation failed: Transaction is invalid.'),
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Order and payment mismatch conflict',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Payment information does not match the order.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/422ResponseSchema'
                )
            ),
        ]
    )]



    public function paymentCallBack(PaymentCallbackRequest $request, Order $order, Payment $payment)
    {
        return $this->paymentCalculationService->paymentCallBack($request, $order, $payment);
    }
}
