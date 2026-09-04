<?php

namespace App\Http\Controllers\Api\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\FilteringRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Market\ProductCategory;
use App\Services\ProductFilterService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class ShopController extends Controller
{

    #[OA\Get(
        path: '/api/shop/{category}',
        summary: 'List and filter products',
        description: 'Retrieve a paginated list of products with smart filtering by search, price range, brands, colors, sizes, tags and stock status.',
        tags: ['Product'],
        parameters: [
            new OA\Parameter(
                name: 'category',
                in: 'path',
                required: false,
                description: 'Optional category slug to filter products by category',
                schema: new OA\Schema(type: 'string', example: 'men')
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                required: false,
                description: 'Search term matched against product name',
                schema: new OA\Schema(type: 'string', example: 'shirt')
            ),
            new OA\Parameter(
                name: 'min_price',
                in: 'query',
                required: false,
                description: 'Minimum price of product variants',
                schema: new OA\Schema(type: 'integer', minimum: 0, example: 50)
            ),
            new OA\Parameter(
                name: 'max_price',
                in: 'query',
                required: false,
                description: 'Maximum price of product variants',
                schema: new OA\Schema(type: 'integer', minimum: 0, example: 200)
            ),
            new OA\Parameter(
                name: 'brands[]',
                in: 'query',
                required: false,
                description: 'Array of brand slugs',
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(type: 'string', example: 'nike')
                )
            ),
            new OA\Parameter(
                name: 'colors[]',
                in: 'query',
                required: false,
                description: 'Array of color slugs',
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(type: 'string', example: 'red')
                )
            ),
            new OA\Parameter(
                name: 'sizes[]',
                in: 'query',
                required: false,
                description: 'Array of size slugs',
                schema: new OA\Schema(
                    type: 'array',
                    items: new OA\Items(type: 'string', example: '38')
                )
            ),
            new OA\Parameter(
                name: 'tag',
                in: 'query',
                required: false,
                description: 'Tag slug to filter products',
                schema: new OA\Schema(type: 'string', example: 'summer')
            ),
            new OA\Parameter(
                name: 'sort',
                in: 'query',
                required: false,
                description: 'Sorting order of the results',
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['newest', 'most_expensive', 'cheapest', 'best_selling', 'trending', 'top_rated'],
                    example: 'most_expensive'
                )
            ),
            new OA\Parameter(
                name: 'in_stock',
                in: 'query',
                required: false,
                description: 'Only include products having in-stock variants',
                schema: new OA\Schema(type: 'integer', enum: [0, 1], example: 0)
            ),
            new OA\Parameter(
                name: 'out_of_stock',
                in: 'query',
                required: false,
                description: 'Only include products having out-of-stock variants',
                schema: new OA\Schema(type: 'integer', enum: [0, 1], example: 0)
            ),
            new OA\Parameter(
                name: 'on_sale',
                in: 'query',
                required: false,
                description: 'Only include products with an active amazing sale on variants',
                schema: new OA\Schema(type: 'integer', enum: [0, 1], example: 1)
            ),
            new OA\Parameter(
                name: 'big_deals',
                in: 'query',
                required: false,
                description: 'Only include products with big deal discounts',
                schema: new OA\Schema(type: 'integer', enum: [0, 1], example: 1)
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                required: false,
                description: 'Page number for pagination',
                schema: new OA\Schema(type: 'integer', minimum: 1, example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Products successfully found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Products were successfully found.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'products',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 30),
                                            new OA\Property(property: 'name', type: 'string', example: 'beautiful lady'),
                                            new OA\Property(property: 'slug', type: 'string', example: 'similique-ut-dignissimos-ex-provident-dolores'),
                                            new OA\Property(property: 'image', type: 'string', format: 'url', example: 'http://127.0.0.1:8000/images/product/2026/04/30/1777579690_main.jpg'),
                                            new OA\Property(property: 'is_liked', type: 'boolean', example: false),
                                            new OA\Property(property: 'total_sold', type: 'integer', example: 0),
                                            new OA\Property(
                                                property: 'matched_variants',
                                                description: 'Representative variant (cheapest in-stock variant matching the active filters)',
                                                type: 'object',
                                                nullable: true,
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 119),
                                                    new OA\Property(property: 'price', type: 'number', example: 68),
                                                    new OA\Property(
                                                        property: 'color',
                                                        type: 'object',
                                                        nullable: true,
                                                        properties: [
                                                            new OA\Property(property: 'id', type: 'integer', example: 5),
                                                            new OA\Property(property: 'name', type: 'string', example: 'red'),
                                                            new OA\Property(property: 'slug', type: 'string', example: 'red'),
                                                            new OA\Property(property: 'hex_code', type: 'string', example: '#ff2424'),
                                                        ]
                                                    ),
                                                    new OA\Property(
                                                        property: 'size',
                                                        type: 'object',
                                                        nullable: true,
                                                        properties: [
                                                            new OA\Property(property: 'id', type: 'integer', example: 7),
                                                            new OA\Property(property: 'name', type: 'string', example: '39'),
                                                            new OA\Property(property: 'slug', type: 'string', example: '39'),
                                                            new OA\Property(property: 'type', type: 'string', nullable: true, example: 'shoes'),
                                                        ]
                                                    ),
                                                    new OA\Property(
                                                        property: 'amazing_sale',
                                                        type: 'object',
                                                        nullable: true,
                                                        properties: [
                                                            new OA\Property(property: 'id', type: 'integer', example: 39),
                                                            new OA\Property(property: 'percentage', type: 'integer', example: 98),
                                                            new OA\Property(property: 'start_date', type: 'string', example: '2026-09-03 12:00:00'),
                                                            new OA\Property(property: 'end_date', type: 'string', example: '2026-09-30 12:00:00'),
                                                            new OA\Property(property: 'is_active', type: 'integer', example: 1),
                                                            new OA\Property(property: 'product_variant_id', type: 'integer', example: 119),
                                                        ]
                                                    ),
                                                    new OA\Property(property: 'final_price', type: 'number', example: 1.36),
                                                    new OA\Property(property: 'stock', type: 'integer', example: 110),
                                                ]
                                            ),
                                        ]
                                    )
                                ),
                                new OA\Property(property: 'active_filters_count', type: 'integer', description: 'Number of currently active filters', example: 1),

                            ]
                        ),
                        new OA\Property(
                            property: 'pagination',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(property: 'last_page', type: 'integer', example: 1),
                                new OA\Property(property: 'per_page', type: 'integer', example: 16),
                                new OA\Property(property: 'total', type: 'integer', example: 4),
                                new OA\Property(property: 'from', type: 'integer', nullable: true, example: 1),
                                new OA\Property(property: 'to', type: 'integer', nullable: true, example: 4),
                                new OA\Property(property: 'next_page_url', type: 'string', nullable: true, example: 'http://127.0.0.1:8000/api/shop?page=2'),
                                new OA\Property(property: 'prev_page_url', type: 'string', nullable: true, example: null),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/422ResponseSchema')
            ),
            new OA\Response(
                response: 404,
                description: 'Category not found',
                content: new OA\JsonContent(ref: '#/components/schemas/404ResponseSchema')
            ),
        ]
    )]


    public function shop(FilteringRequest $request, ?ProductCategory $category, ProductFilterService $productFilterService)
    {
        $data = $request->validated();

        $result = $productFilterService->productFilter($data, $category ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'Products were successfully found.',
            'data' => [
                'products' => ProductListResource::collection($result['products']->items()),
                'active_filters_count' => $result['activeFiltersCount'],
            ],
            'pagination' => [
                'current_page' => $result['products']->currentPage(),
                'last_page' => $result['products']->lastPage(),
                'per_page' => $result['products']->perPage(),
                'total' => $result['products']->total(),
                'from' => $result['products']->firstItem(),
                'to' => $result['products']->lastItem(),
                'next_page_url' => $result['products']->nextPageUrl(),
                'prev_page_url' => $result['products']->previousPageUrl(),
            ],
        ]);
    }
}
