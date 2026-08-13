<?php

namespace App\Http\Controllers\Api\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\AddToCartRequest;
use App\Http\Requests\Api\Cart\ShopingCartRequest;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\CommonDiscountResource;
use App\Http\Resources\CouponResource;
use App\Models\Market\CartItem;
use App\Services\CartManager;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Cart', description: 'Operations attributed to the users shoping cart')]


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
        description: 'Add a product variant to the authenticated user\'s shoping cart',
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
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/401ResponseSchema'
                )
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



    #[OA\Get(
        path: "/api/shoping-cart",
        summary: "Get user cart information",
        description: "This method returns the user's shopping cart information along with general discounts, coupons applied, and final price calculations.",
        tags: ["Cart"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "coupon",
                in: "query",
                description: "Discount code (coupon) to apply to cart (optional)",
                required: false,
                example: "testy"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Shopping cart information successfully received",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string", example: "users shoping cart"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                // Cart Items 
                                new OA\Property(
                                    property: "cartItems",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 87),
                                            new OA\Property(property: "quantity", type: "integer", example: 2),
                                            new OA\Property(
                                                property: "product_variant",
                                                type: "object",
                                                properties: [
                                                    new OA\Property(property: "id", type: "integer", example: 150),
                                                    new OA\Property(property: "price", type: "string", example: "66"),
                                                    new OA\Property(
                                                        property: "product",
                                                        type: "object",
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer", example: 40),
                                                            new OA\Property(property: "name", type: "string", example: "product name"),
                                                            new OA\Property(property: "slug", type: "string", example: "product slug"),
                                                            new OA\Property(property: "image", type: "string", example: "images/product/2026/06/13/1781302840_main.jpg")
                                                        ]
                                                    ),
                                                    new OA\Property(
                                                        property: "color",
                                                        type: "object",
                                                        nullable: true,
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer", example: 6),
                                                            new OA\Property(property: "name", type: "string", example: "gray"),
                                                            new OA\Property(property: "slug", type: "string", example: "gray"),
                                                            new OA\Property(property: "hex_code", type: "string", example: "#999999")
                                                        ]
                                                    ),
                                                    new OA\Property(
                                                        property: "size",
                                                        type: "object",
                                                        nullable: true,
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer", example: 9),
                                                            new OA\Property(property: "name", type: "string", example: "xl"),
                                                            new OA\Property(property: "slug", type: "string", example: "xl"),
                                                            new OA\Property(property: "type", type: "string", nullable: true, example: null)
                                                        ]
                                                    ),
                                                    new OA\Property(
                                                        property: "amazing_sale",
                                                        type: "object",
                                                        nullable: true,
                                                        properties: [
                                                            new OA\Property(property: "id", type: "integer", example: 9),
                                                            new OA\Property(property: "percentage", type: "integer", example: 10),
                                                            new OA\Property(property: "start_date", type: "string", example: "2026-08-07 12:00:00"),
                                                            new OA\Property(property: "end_date", type: "string", nullable: true, example: "2026-08-26 12:00:00"),
                                                            new OA\Property(property: "is_active", type: "integer", nullable: true, example: 1),
                                                            new OA\Property(property: "product_variant_id", type: "integer", nullable: true, example: 158)
                                                        ]
                                                    )
                                                ]
                                            )
                                        ]
                                    )
                                ),
                                // ---- Common Discount ----
                                new OA\Property(
                                    property: "commonDiscount",
                                    type: "object",
                                    nullable: true,
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 2),
                                        new OA\Property(property: "title", type: "string", example: "روز کارگر"),
                                        new OA\Property(property: "percentage", type: "integer", example: 10),
                                        new OA\Property(property: "discount_ceiling", type: "integer", nullable: true, example: 15),
                                        new OA\Property(property: "minimal_order_amount", type: "integer", example: 100),
                                        new OA\Property(property: "start_date", type: "string", example: "2025-11-02 16:00:00"),
                                        new OA\Property(property: "end_date", type: "string", example: "2026-08-31 14:00:00")
                                    ]
                                ),
                                // ---- Coupon ----
                                new OA\Property(
                                    property: "coupon",
                                    type: "object",
                                    nullable: true,
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 8),
                                        new OA\Property(property: "code", type: "string", example: "testy"),
                                        new OA\Property(property: "amount", type: "string", example: "10.00"),
                                        new OA\Property(property: "discount_ceiling", type: "integer", nullable: true, example: null),
                                        new OA\Property(property: "amount_type", type: "integer", description: "0 for percentage, 1 for flat value", example: 0),
                                        new OA\Property(property: "start_date", type: "string", example: "2026-08-09 12:00:00"),
                                        new OA\Property(property: "end_date", type: "string", example: "2026-08-26 12:00:00")
                                    ]
                                ),
                                // ---- Totals ----
                                new OA\Property(
                                    property: "totals",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "totalCartPrice", type: "number", format: "float", example: 106.92),
                                        new OA\Property(property: "productDiscounts", type: "number", format: "float", example: 0),
                                        new OA\Property(property: "productPrices", type: "number", format: "float", example: 132),
                                        new OA\Property(property: "commonDiscountAmount", type: "number", format: "float", example: 13.2),
                                        new OA\Property(property: "couponDiscount", type: "number", format: "float", example: 11.88),
                                        new OA\Property(property: "commonDiscountPercentage", type: "integer", example: 10)
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error (such as invalid or expired discount coupon)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Validation failed."),
                        new OA\Property(
                            property: "errors",
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "coupon",
                                    type: "array",
                                    items: new OA\Items(type: "string"),
                                    example: ["The discount code entered is not valid or has expired."]
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
        ]
    )]


    public function shopingCart(ShopingCartRequest $request)
    {
        $data = $request->validated();

        $result = $this->cartManager->shopingCart($data['coupon'] ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'users shoping cart',
            'data' => [
                'cartItems' => CartItemResource::collection($result['cartItems']),
                'commonDiscount' => $result['commonDiscount'] ? new CommonDiscountResource($result['commonDiscount']) : null,
                'coupon' => $result['coupon'] ? new CouponResource($result['coupon']) : null,
                'totals' => $result['totals']
            ]
        ], 200);
    }



    #[OA\Get(
        path: '/api/remove-from-cart/{cartItem}',
        summary: 'Remove an item from the shopping cart',
        description: 'Deletes a cart item that belongs to the authenticated user.',
        tags: ['Cart'],
        security: [
            ['sanctum' => []]
        ],
        parameters: [
            new OA\Parameter(
                name: 'cartItem',
                description: 'The ID of the cart item to remove',
                in: 'path',
                required: true,
                example: 15,
                schema: new OA\Schema(
                    type: 'integer',
                    minimum: 1
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cart item deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'success'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Cart item deleted successfully'
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
                response: 409,
                description: 'Cart item cannot be deleted because it is locked or involved in a payment process',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'error'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'This item is in an active payment process and cannot be deleted at this time. If you wish to cancel, cancel the payment; otherwise, the item will be available for deletion after the payment deadline.'
                        )
                    ]
                )
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

    public function removeFromCart(CartItem $cartItem)
    {
        $this->cartManager->removeFromCart($cartItem);

        return response()->json([
            'status' => 'success',
            'message' => 'Cart item deleted successfuly'
        ]);
    }




    #[OA\Post(
        path: '/api/shoping-cart/update',
        operationId: 'updateCartItem',
        summary: 'Update quantity of a cart item',
        description: 'Updates the quantity of an existing cart item. The quantity must be between 1 and 10.',
        security: [['sanctum' => []]],
        tags: ['Cart'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['cart_item_id', 'quantity'],
                properties: [
                    new OA\Property(
                        property: 'cart_item_id',
                        type: 'integer',
                        description: 'ID of the cart item to update',
                        example: 42,
                    ),
                    new OA\Property(
                        property: 'quantity',
                        type: 'integer',
                        minimum: 1,
                        maximum: 10,
                        description: 'New quantity (1-10)',
                        example: 3,
                    ),
                    new OA\Property(
                        property: 'coupon',
                        type: 'string',
                        description: 'coupon code',
                        example: 'testy',
                    ),
                ],
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cart item updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Cart item updated successfully'),

                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'totalItemPrice', type: 'number', format: 'float', example: 220),
                                new OA\Property(property: 'price', type: 'number', format: 'float', example: 110),
                                new OA\Property(property: 'finalPrice', type: 'number', format: 'float', example: 110),
                                new OA\Property(property: 'discount', type: 'number', format: 'float', nullable: true, example: null),

                                new OA\Property(property: 'cart_item_id', type: 'integer', example: 97),
                                new OA\Property(property: 'new_quantity', type: 'integer', example: 2),
                                new OA\Property(property: 'totalProductsQuantity', type: 'integer', example: 2),

                                new OA\Property(property: 'totalCartPrice', type: 'number', format: 'float', example: 184.5),
                                new OA\Property(property: 'productPrices', type: 'number', format: 'float', example: 220),
                                new OA\Property(property: 'productDiscounts', type: 'number', format: 'float', example: 0),

                                new OA\Property(property: 'commonDiscountAmount', type: 'number', format: 'float', example: 15),
                                new OA\Property(property: 'commonDiscountPercentage', type: 'integer', example: 10),

                                new OA\Property(property: 'couponApplied', type: 'boolean', example: true),
                                new OA\Property(property: 'couponDiscount', type: 'number', format: 'float', example: 20.5),
                            ]
                        ),
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
                response: 404,
                description: 'Cart item not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'error'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Cart item not found'
                        )
                    ]
                )
            ),

            new OA\Response(
                response: 409,
                description: 'Insufficient stock',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'error'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Requested quantity is not available.'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'The cart item id field is required. / The quantity field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            description: 'Dictionary of field errors',
                            properties: [
                                new OA\Property(
                                    property: 'cart_item_id',
                                    type: 'string',
                                    example: "The cart item id field is required."
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
        ],
    )]

    public function updateCart(Request $request)
    {
        $data = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|between:1,10',
            'coupon' => 'nullable|max:120|min:2'
        ]);

        $result = $this->cartManager->updateCart($data, $data['coupon'] ?? null);


        return response()->json([
            'status' => 'success',
            'message' => 'Cart item updated successfuly',
            'data' => $result,
        ]);
    }





    #[OA\Post(
        path: '/api/shoping-cart/coupon',
        tags: ['Cart'],
        summary: 'Apply a discount coupon to the cart',
        description: 'Validates the coupon, checks single-use and ownership, then returns updated totals.',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['coupon'],
                properties: [
                    new OA\Property(property: 'coupon', type: 'string', minLength: 2, maxLength: 120, example: 'testy'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Coupon applied successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Discount coupon applied successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'finalPrice', type: 'number', format: 'float', example: 184.5),
                                new OA\Property(property: 'couponDiscountAmount', type: 'number', format: 'float', example: 20.5),
                                new OA\Property(property: 'coupon', type: 'string', example: 'testy'),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Coupon error — invalid code, already used, private coupon, or empty cart',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            enum: [
                                'invalid code',
                                'You have already used this discount code',
                                'This discount code is specific to a specific user',
                                'Cart is empty',
                            ],
                            example: 'invalid code'
                        ),
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
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'The coupon field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            description: 'Dictionary of field errors',
                            properties: [
                                new OA\Property(
                                    property: 'coupon',
                                    type: 'array',
                                    description: 'Validation messages for the coupon field',
                                    items: new OA\Items(
                                        type: 'string',
                                        enum: [
                                            'The coupon field is required.',
                                            'The coupon field must be at least 2 characters.',
                                            'The coupon field must not be greater than 120 characters.',
                                        ]
                                    ),
                                ),
                            ]
                        )
                    ]
                )
            ),

        ]
    )]


    public function coupon(Request $request)
    {
        $data = $request->validate([
            'coupon' => 'required|max:120|min:2'
        ]);

        $result = $this->cartManager->applyCoupon($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Discount coupon applied successfully.',
            'data' => [
                'finalPrice' => $result['finalPrice'],
                'couponDiscountAmount' => $result['couponDiscountAmount'],
                'coupon' => $result['coupon'],
            ]
        ]);
    }
}
