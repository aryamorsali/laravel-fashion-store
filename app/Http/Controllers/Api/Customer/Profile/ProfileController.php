<?php

namespace App\Http\Controllers\Api\Customer\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserOrdersResource;
use App\Models\Market\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Order', description: 'User orders list, Order detail')]

class ProfileController extends Controller
{

    #[OA\Get(
        path: '/api/my-orders',
        security: [["sanctum" => []]],
        summary: 'Get user orders list',
        description: 'Retrieve a lightweight paginated list of orders placed by the authenticated user.',
        tags: ['Order'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                in: 'query',
                required: false,
                description: 'Page number for pagination',
                schema: new OA\Schema(type: 'integer', default: 1, example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User orders successfully retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'User orders successfully found'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 41),
                                    new OA\Property(
                                        property: 'status',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'order_status', type: 'string', example: 'canceled'),
                                            new OA\Property(property: 'payment_status', type: 'string', example: 'unpaid'),
                                            new OA\Property(property: 'delivery_status', type: 'string', example: 'sending'),
                                        ]
                                    ),
                                    new OA\Property(
                                        property: 'pricing',
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'final_amount', type: 'integer', example: 51),
                                            new OA\Property(property: 'total_discount', type: 'integer', example: 0),
                                            new OA\Property(property: 'delivery_amount', type: 'integer', example: 7),
                                        ]
                                    ),
                                    new OA\Property(property: 'delivery_date', type: 'string', example: '2026-09-04 23:44:56'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-08-30T20:14:56.000000Z'),
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'pagination',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(property: 'last_page', type: 'integer', example: 2),
                                new OA\Property(property: 'per_page', type: 'integer', example: 1),
                                new OA\Property(property: 'total', type: 'integer', example: 2),
                                new OA\Property(property: 'from', type: 'integer', example: 1),
                                new OA\Property(property: 'to', type: 'integer', example: 1),
                                new OA\Property(property: 'next_page_url', type: 'string', nullable: true, example: 'http://127.0.0.1:8000/api/my-orders?page=2'),
                                new OA\Property(property: 'prev_page_url', type: 'string', nullable: true, example: null),
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
        ]
    )]


    public function myOrders()
    {
        $orders = Auth::user()->orders()->with(['orderItems', 'orderItems.productVariant', 'orderItems.productVariant.product'])->orderBy('created_at', 'desc')->paginate(1);

        return response()->json([
            'status' => 'success',
            'message' => 'User orders successfully found',
            'data' => UserOrdersResource::collection($orders),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
                'next_page_url' => $orders->nextPageUrl(),
                'prev_page_url' => $orders->previousPageUrl(),
            ],
        ]);
    }


    #[OA\Get(
        path: '/api/my-orders/detail/{order}',
        security: [["sanctum" => []]],
        summary: 'Get user order details',
        description: 'Full details of an authenticated user order.',
        tags: ['Order'],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                description: 'The ID of the order',
                schema: new OA\Schema(type: 'integer', example: 41)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User order details successfully retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'User order details'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 41),
                                new OA\Property(
                                    property: 'status',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'order_status', type: 'string', example: 'canceled'),
                                        new OA\Property(property: 'payment_status', type: 'string', example: 'unpaid'),
                                        new OA\Property(property: 'delivery_status', type: 'string', example: 'sending'),
                                    ]
                                ),
                                new OA\Property(
                                    property: 'pricing',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'final_amount', type: 'integer', example: 51),
                                        new OA\Property(property: 'total_products_discount', type: 'integer', example: 0),
                                        new OA\Property(property: 'total_discount', type: 'integer', example: 0),
                                        new OA\Property(property: 'coupon_discount', type: 'integer', example: 0),
                                        new OA\Property(property: 'common_discount', type: 'integer', example: 0),
                                        new OA\Property(property: 'delivery_amount', type: 'integer', example: 7),
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
                                        new OA\Property(property: 'coupon', type: 'object', nullable: true, example: null),
                                        new OA\Property(
                                            property: 'common_discount',
                                            nullable: true,

                                            type: 'object',
                                            properties: [
                                                new OA\Property(property: 'id', type: 'integer', example: 185),
                                                new OA\Property(property: 'order_id', type: 'integer', example: 41),
                                                new OA\Property(property: 'product_variant_id', type: 'integer', example: 128),
                                                new OA\Property(property: 'amazing_sale_id', type: 'integer', nullable: true, example: null),
                                                new OA\Property(property: 'quantity', type: 'integer', example: 1),
                                                new OA\Property(
                                                    property: 'product_snapshot',
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'has_color', type: 'integer', example: 0),
                                                        new OA\Property(property: 'has_size', type: 'integer', example: 1),
                                                        new OA\Property(property: 'name', type: 'string', example: 'tenetur accusamus nisi'),
                                                        new OA\Property(property: 'slug', type: 'string', example: 'consequuntur-dolorem-eaque-repudiandae-ipsa-nam-est-quae'),
                                                        new OA\Property(property: 'description', type: 'string', example: '<p>Eum voluptate esse ipsam. Quia cupiditate autem est consequatur fuga sit excepturi.</p>'),
                                                        new OA\Property(
                                                            property: 'image',
                                                            type: 'object',
                                                            properties: [
                                                                new OA\Property(
                                                                    property: 'indexArray',
                                                                    type: 'object',
                                                                    properties: [
                                                                        new OA\Property(property: 'large', type: 'string', example: 'images/product/2026/05/04/1777842780_large.jpg'),
                                                                        new OA\Property(property: 'main', type: 'string', example: 'images/product/2026/05/04/1777842780_main.jpg'),
                                                                        new OA\Property(property: 'small', type: 'string', example: 'images/product/2026/05/04/1777842780_small.jpg'),
                                                                    ]
                                                                ),
                                                                new OA\Property(property: 'directory', type: 'string', example: 'images/product/2026/05/04'),
                                                                new OA\Property(property: 'currentImage', type: 'string', example: 'main'),
                                                            ]
                                                        ),
                                                        new OA\Property(property: 'base_price', type: 'string', example: '38.00'),
                                                        new OA\Property(property: 'brand_id', type: 'integer', example: 14),
                                                        new OA\Property(property: 'category_id', type: 'integer', example: 20),
                                                        new OA\Property(property: 'status', type: 'string', example: 'published'),
                                                        new OA\Property(property: 'published_at', type: 'string', format: 'date-time', example: '2026-04-30T19:59:00.000000Z'),
                                                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-04-30T19:59:45.000000Z'),
                                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-06-19T19:35:53.000000Z'),
                                                        new OA\Property(property: 'deleted_at', type: 'string', nullable: true, example: null),
                                                    ]
                                                ),
                                                new OA\Property(property: 'amazing_sale_snapshot', type: 'object', nullable: true, example: null),
                                                new OA\Property(property: 'amazing_sale_discount_amount', type: 'integer', example: 0),
                                                new OA\Property(property: 'final_product_price', type: 'integer', example: 44),
                                                new OA\Property(property: 'final_total_price', type: 'integer', example: 44),
                                            ]
                                        )
                                    ]
                                ),
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
                response: 403,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/403ResponseSchema'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Order not found',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/404ResponseSchema'
                )
            ),
        ]
    )]


    public function myOrdersDetail(Order $order)
    {
        $order->load(['orderItems', 'orderItems.productVariant', 'orderItems.productVariant.product']);
        return response()->json([
            'status' => 'success',
            'message' => 'User order details',
            'data' => new  OrderResource($order),
        ]);
    }
}
