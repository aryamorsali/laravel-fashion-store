<?php

namespace App\Http\Controllers\Api\Customer\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserOrdersResource;
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
}
