<?php

namespace App\Http\Controllers\Api\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ProductResource;
use App\Models\Market\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use OpenApi\Attributes as OA;


#[OA\Tag(name: 'Product', description: 'Product details, add comments')]

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }



    #[OA\Get(
        path: '/api/product/{product}',
        summary: 'Get product detail',
        description: 'Retrieve complete product detail by slug, including variants, comments, related products and rating',
        tags: ['Product'],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product slug',
                schema: new OA\Schema(type: 'string', example: 'blue-shirt')
            ),
            new OA\Parameter(
                name: 'variant',
                in: 'query',
                required: false,
                description: 'Selected variant id',
                schema: new OA\Schema(type: 'integer', example: 95)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product detail retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'success'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'product detail'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'product',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 26),
                                        new OA\Property(property: 'name', type: 'string', example: 'Blue shirt'),
                                        new OA\Property(property: 'slug', type: 'string', example: 'blue-shirt'),
                                        new OA\Property(property: 'image', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/product/2026/02/26/1772130441_main.jpg'),
                                        new OA\Property(property: 'description', type: 'string', example: '<p>stest</p>'),
                                        new OA\Property(
                                            property: 'category',
                                            type: 'object',
                                            nullable: true,
                                            properties: [
                                                new OA\Property(property: 'id', type: 'integer', example: 12),
                                                new OA\Property(property: 'name', type: 'string', example: 'Men'),
                                                new OA\Property(property: 'description', type: 'string', example: '<p>Men Men Men</p>'),
                                                new OA\Property(property: 'slug', type: 'string', example: 'men'),
                                                new OA\Property(property: 'image', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/product-category/2025/12/26/1766779279_main.jpg'),
                                                new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
                                            ]
                                        ),
                                        new OA\Property(
                                            property: 'brand',
                                            type: 'object',
                                            nullable: true,
                                            properties: [
                                                new OA\Property(property: 'id', type: 'integer', example: 14),
                                                new OA\Property(property: 'name', type: 'string', example: 'nike'),
                                                new OA\Property(property: 'slug', type: 'string', example: 'nike'),
                                                new OA\Property(property: 'logo', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/brand/2026/08/17/1786994228_main.jpg'),
                                            ]
                                        ),
                                        new OA\Property(
                                            property: 'gallery',
                                            type: 'array',
                                            items: new OA\Items(
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 13),
                                                    new OA\Property(property: 'image', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/product_gallery/2026/08/17/1786996835_main.jpg'),
                                                ]
                                            )
                                        ),
                                        new OA\Property(
                                            property: 'attributes',
                                            type: 'array',
                                            items: new OA\Items(
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 14),
                                                    new OA\Property(property: 'name', type: 'string', example: 'material'),
                                                    new OA\Property(property: 'unit', type: 'string', nullable: true, example: null),
                                                    new OA\Property(property: 'value', type: 'string', example: 'Plastic'),
                                                ]
                                            )
                                        ),
                                        new OA\Property(property: 'is_liked', type: 'boolean', example: false),
                                        new OA\Property(property: 'total_sold', type: 'integer', example: 0),
                                    ]
                                ),
                                new OA\Property(
                                    property: 'variantsForJs',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 93),
                                            new OA\Property(property: 'color_id', type: 'integer', nullable: true, example: 2),
                                            new OA\Property(property: 'color_name', type: 'string', nullable: true, example: 'green'),
                                            new OA\Property(property: 'color_hex', type: 'string', nullable: true, example: '#59e84f'),
                                            new OA\Property(property: 'size_id', type: 'integer', nullable: true, example: 6),
                                            new OA\Property(property: 'size_name', type: 'string', nullable: true, example: '38'),
                                            new OA\Property(property: 'price', type: 'number', format: 'float', example: 423),
                                            new OA\Property(property: 'final_price', type: 'number', format: 'float', example: 423),
                                            new OA\Property(property: 'stock', type: 'integer', example: 12),
                                            new OA\Property(property: 'percentage', type: 'integer', nullable: true, example: null),
                                            new OA\Property(property: 'expire_at', type: 'string', format: 'date-time', nullable: true, example: null),
                                        ]
                                    )
                                ),
                                new OA\Property(property: 'hasSellableVariant', type: 'boolean', example: true),
                                new OA\Property(
                                    property: 'approvedComments',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 18),
                                            new OA\Property(property: 'body', type: 'string', example: 'good material'),
                                            new OA\Property(property: 'rating', type: 'integer', nullable: true, example: 4),
                                            new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
                                            new OA\Property(
                                                property: 'created_at',
                                                type: 'string',
                                                format: 'date-time',
                                                example: '2026-04-12T20:24:08.000000Z'
                                            ),
                                            new OA\Property(
                                                property: 'user',
                                                type: 'object',
                                                nullable: true,
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 10),
                                                    new OA\Property(property: 'first_name', type: 'string', example: 'arya'),
                                                    new OA\Property(property: 'last_name', type: 'string', example: 'morsali'),
                                                    new OA\Property(property: 'full_name', type: 'string', example: 'arya morsali'),
                                                    new OA\Property(property: 'profile_photo', type: 'string', format: 'uri', nullable: true, example: 'http://127.0.0.1:8000/images/users/2025/11/18/1763412881_main.jpg'),
                                                ]
                                            ),
                                            new OA\Property(
                                                property: 'children',
                                                type: 'array',
                                                items: new OA\Items(
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 19),
                                                        new OA\Property(property: 'body', type: 'string', example: 'good'),
                                                        new OA\Property(property: 'rating', type: 'integer', nullable: true, example: null),
                                                        new OA\Property(property: 'parent_id', type: 'integer', example: 18),
                                                        new OA\Property(
                                                            property: 'created_at',
                                                            type: 'string',
                                                            format: 'date-time',
                                                            example: '2026-04-13T10:15:00.000000Z'
                                                        ),
                                                        new OA\Property(
                                                            property: 'user',
                                                            type: 'object',
                                                            nullable: true,
                                                            properties: [
                                                                new OA\Property(property: 'id', type: 'integer', example: 11),
                                                                new OA\Property(property: 'first_name', type: 'string', example: 'ali'),
                                                                new OA\Property(property: 'last_name', type: 'string', example: 'abdi'),
                                                                new OA\Property(property: 'full_name', type: 'string', example: 'ali abdi'),
                                                                new OA\Property(property: 'profile_photo', type: 'string', format: 'uri', nullable: true, example: null),
                                                            ]
                                                        ),
                                                    ]
                                                )
                                            ),
                                        ]
                                    )
                                ),
                                new OA\Property(

                                    property: 'relatedProducts',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',

                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 27),
                                            new OA\Property(property: 'name', type: 'string', example: 'clock'),
                                            new OA\Property(property: 'slug', type: 'string', example: 'clock'),
                                            new OA\Property(property: 'image', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/product/2026/02/25/1771971480_main.jpg'),
                                            new OA\Property(property: 'description', type: 'string', example: '<p>clockclockclockclock</p>'),
                                            new OA\Property(
                                                property: 'category',
                                                type: 'object',
                                                nullable: true,
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 12),
                                                    new OA\Property(property: 'name', type: 'string', example: 'Men'),
                                                    new OA\Property(property: 'description', type: 'string', example: '<p>Men Men Men</p>'),
                                                    new OA\Property(property: 'slug', type: 'string', example: 'men'),
                                                    new OA\Property(property: 'image', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/product-category/2025/12/26/1766779279_main.jpg'),
                                                    new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
                                                ]
                                            ),
                                            new OA\Property(
                                                property: 'brand',
                                                type: 'object',
                                                nullable: true,
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 14),
                                                    new OA\Property(property: 'name', type: 'string', example: 'nike'),
                                                    new OA\Property(property: 'slug', type: 'string', example: 'nike'),
                                                    new OA\Property(property: 'logo', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/brand/2026/08/17/1786994228_main.jpg'),
                                                ]
                                            ),
                                            new OA\Property(
                                                property: 'gallery',
                                                type: 'array',
                                                items: new OA\Items(
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 13),
                                                        new OA\Property(property: 'image', type: 'string', format: 'uri', example: 'http://127.0.0.1:8000/images/product_gallery/2026/08/17/1786996835_main.jpg'),
                                                    ]
                                                )
                                            ),
                                            new OA\Property(
                                                property: 'attributes',
                                                type: 'array',
                                                items: new OA\Items(
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 14),
                                                        new OA\Property(property: 'name', type: 'string', example: 'material'),
                                                        new OA\Property(property: 'unit', type: 'string', nullable: true, example: null),
                                                        new OA\Property(property: 'value', type: 'string', example: 'Plastic'),
                                                    ]
                                                )
                                            ),
                                            new OA\Property(property: 'is_liked', type: 'boolean', example: true),
                                            new OA\Property(property: 'total_sold', type: 'integer', example: 33),
                                        ]
                                    )
                                ),
                                new OA\Property(property: 'aveRating', type: 'number', format: 'float', example: 4),
                                new OA\Property(property: 'selectedVariantId', type: 'integer', nullable: true, example: 95),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Product not found or not published',
                content: new OA\JsonContent(ref: '#/components/schemas/404ResponseSchema')
            ),
        ]
    )]


    public function product(Product $product, Request $request)
    {

        $result = $this->productService->productDetail($product, $request);

        return response()->json([
            'status' => 'success',
            'message' => 'product detail',
            'data' => [
                'product' => new ProductResource($result['product']),
                'variantsForJs' => $result['variantsForJs'],
                'hasSellableVariant' => $result['hasSellableVariant'],
                'approvedComments' => CommentResource::collection($result['approvedComments']),
                'relatedProducts' => ProductResource::collection($result['relatedProducts']),
                'aveRating' => $result['aveRating'],
                'selectedVariantId' => $result['selectedVariantId'],
            ]
        ]);
    }



    #[OA\Post(
        path: '/api/product/{product}/add-comment',
        security: [['sanctum' => []]],
        summary: 'Add comment',
        description: 'Add a comment for a product',
        tags: ['Product'],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product slug',
                schema: new OA\Schema(type: 'string', example: 'blue-shirt')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['rating', 'body'],
                properties: [
                    new OA\Property(
                        property: 'rating',
                        type: 'integer',
                        minimum: 1,
                        maximum: 5,
                        description: 'User rating for this product between 1 and 5',
                        example: 5
                    ),
                    new OA\Property(
                        property: 'body',
                        type: 'string',
                        description: 'User opinion about this product',
                        example: 'nice'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Comment successfully registered',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'success'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Your comment has been recorded and will be displayed after review.'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'comment',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 38),
                                        new OA\Property(property: 'body', type: 'string', example: 'nice'),
                                        new OA\Property(property: 'rating', type: 'integer', example: 5),
                                        new OA\Property(property: 'approved', type: 'boolean', example: false),
                                        new OA\Property(
                                            property: 'parent_id',
                                            type: 'integer',
                                            nullable: true,
                                            example: null
                                        ),
                                        new OA\Property(
                                            property: 'created_at',
                                            type: 'string',
                                            format: 'date-time',
                                            example: '2026-08-17T17:40:49.000000Z'
                                        ),
                                    ]
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(ref: '#/components/schemas/401ResponseSchema')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/422ResponseSchema')
            ),
            new OA\Response(
                response: 429,
                description: 'Too many requests',
                content: new OA\JsonContent(ref: '#/components/schemas/429ResponseSchema')
            ),
        ]
    )]

    public function addComment(Product $product, CommentRequest $request)
    {

        $data  = $request->validated();

        $comment = $this->productService->addComment($product, $data);


        return response()->json([
            'status' => 'success',
            'message' => 'Your comment has been recorded and will be displayed after review.',
            'data' => [
                'comment' => new CommentResource($comment),
            ],
        ]);
    }
}
