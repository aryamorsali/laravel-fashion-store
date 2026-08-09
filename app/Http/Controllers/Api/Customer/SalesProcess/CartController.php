<?php

namespace App\Http\Controllers\Api\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Services\CartManager;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Cart', description: 'Operations attributed to the users shopping cart')]


class CartController extends Controller
{
    protected $cartManager;

    public function __construct(CartManager $cartManager)
    {
        $this->cartManager = $cartManager;
    }


    #[OA\Post(
        path: '/api/add-to-cart',
        security: [["sanctum" => []]],
        summary: 'Add to cart',
        description: 'Add a product variant to the authenticated user\'s shopping cart',
        tags: ['Cart'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['variant_id', 'quantity'],
                properties: [
                    new OA\Property(
                        property: 'variant_id',
                        type: 'integer',
                        description: 'The ID of the product variant',
                        example: 116
                    ),
                    new OA\Property(
                        property: 'quantity',
                        type: 'integer',
                        description: 'The number of items to add. Must be between 1 and 10.',
                        example: 7
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product successfully added to cart',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Product successfully added to your cart!'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'cart_item_id', type: 'integer', example: 89),
                                new OA\Property(property: 'quantity', type: 'integer', example: 7),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error or Out of stock',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid. / Sorry, there isn’t enough stock for this item.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            description: 'Dictionary of field errors',
                            properties: [
                                new OA\Property(
                                    property: 'variant_id',
                                    type: 'string',
                                    example: 'The selected variant id is invalid.'
                                ),
                                new OA\Property(
                                    property: 'quantity',
                                    type: 'string',
                                    example: 'The quantity field is required.'
                                ),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated'
            ),
            new OA\Response(
                response: 429,
                description: 'Too many requests (throttled)'
            )
        ]
    )]


    public function addToCart(AddToCartRequest $request)
    {
        $data = $request->validated();

        $cartItem = $this->cartManager->addToCart($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product successfully added to your cart!',
            'data' => [
                'cart_item_id' => $cartItem->id,
                'quantity' => $cartItem->quantity
            ]
        ], 200);
    }
}
