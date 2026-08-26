<?php

namespace App\Http\Controllers\Api\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\AddressCouponRequest;
use App\Http\Requests\Api\Address\StoreAddressRequest;
use App\Http\Requests\Api\Address\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\DeliveryResource;
use App\Http\Resources\ProvinceResource;
use App\Models\Market\Address;
use App\Models\Market\Province;
use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Address', description: 'User address related operations')]

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }



    #[OA\Get(
        path: '/api/address-and-delivery',
        summary: 'Get addresses, shipping methods, and shopping cart summaries',
        description: 'This endpoint returns a list of user addresses, active shipping methods, discounts, and the final cart price.',
        security: [['sanctum' => []]],
        tags: ['Address'],
        parameters: [
            new OA\Parameter(
                name: 'coupon',
                in: 'query',
                required: false,
                description: 'Optional discount coupon code to apply and calculate in amounts',
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Information received successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'users addresses'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'addresses',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 3),
                                            new OA\Property(property: 'recipient_name', type: 'string', example: 'arya'),
                                            new OA\Property(property: 'mobile', type: 'string', example: '12345678901'),
                                            new OA\Property(
                                                property: 'province',
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                                    new OA\Property(property: 'name', type: 'string', example: 'Tehran'),
                                                ]
                                            ),
                                            new OA\Property(
                                                property: 'city',
                                                type: 'object',
                                                properties: [
                                                    new OA\Property(property: 'id', type: 'integer', example: 3),
                                                    new OA\Property(property: 'name', type: 'string', example: 'Shahriar'),
                                                ]
                                            ),
                                            new OA\Property(property: 'address', type: 'string', example: 'Governorship Alley 2'),
                                            new OA\Property(property: 'postal_code', type: 'string', example: '1234567890'),
                                            new OA\Property(property: 'no', type: 'string', example: '12'),
                                            new OA\Property(property: 'unit', type: 'string', example: '4'),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'deliveries',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', type: 'string', example: 'motor peyck'),
                                            new OA\Property(property: 'delivery_cost', type: 'number', example: 7),
                                            new OA\Property(property: 'delivery_days', type: 'integer', nullable: true, example: 5),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: 'totals',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'totalCartPrice', type: 'number', format: 'float', example: 283.5),
                                        new OA\Property(property: 'productDiscounts', type: 'number', example: 0),
                                        new OA\Property(property: 'productPrices', type: 'number', example: 330),
                                        new OA\Property(property: 'commonDiscountAmount', type: 'number', example: 15),
                                        new OA\Property(property: 'couponDiscount', type: 'number', format: 'float', example: 31.5),
                                        new OA\Property(property: 'commonDiscountPercentage', type: 'number', example: 10),
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
                content: new OA\JsonContent(
                    ref: '#/components/schemas/401ResponseSchema'
                )
            ),

            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/422ResponseSchema')
            ),

        ]
    )]

    public function addressAndDelivery(AddressCouponRequest $request)
    {
        $data = $request->validated();

        $result = $this->addressService->addressAndDelivery($data['coupon'] ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'Information received successfully',
            'data' => [
                'addresses' => AddressResource::collection($result['addresses']),
                'deliveries' => DeliveryResource::collection($result['deliveries']),
                'totals' => $result['totals'],
            ]
        ]);
    }


    #[OA\Post(
        path: '/api/store-address',
        summary: 'Register a new user address',
        description: 'Creates and stores a new address for the authenticated user.',
        security: [
            ['sanctum' => []]
        ],
        tags: ['Address'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: [
                    'recipient_name',
                    'city_id',
                    'province_id',
                    'address',
                    'postal_code',
                    'mobile',
                ],
                properties: [
                    new OA\Property(
                        property: 'recipient_name',
                        type: 'string',
                        example: 'ali rezaii'
                    ),
                    new OA\Property(
                        property: 'city_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'province_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'address',
                        type: 'string',
                        example: 'tehran valiasr street'
                    ),
                    new OA\Property(
                        property: 'postal_code',
                        type: 'string',
                        example: '1234567890'
                    ),
                    new OA\Property(
                        property: 'no',
                        type: 'string',
                        nullable: true,
                        example: '12'
                    ),
                    new OA\Property(
                        property: 'unit',
                        type: 'string',
                        nullable: true,
                        example: '3'
                    ),
                    new OA\Property(
                        property: 'mobile',
                        type: 'string',
                        example: '09121234567'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Address successfully registered',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Address successfully registered.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 3),
                                new OA\Property(property: 'recipient_name', type: 'string', example: 'soheila alipour'),
                                new OA\Property(property: 'mobile', type: 'string', example: '98765432100'),
                                new OA\Property(
                                    property: 'province',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'Tehran'),
                                    ]
                                ),
                                new OA\Property(
                                    property: 'city',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 3),
                                        new OA\Property(property: 'name', type: 'string', example: 'Shahriar'),
                                    ]
                                ),
                                new OA\Property(property: 'address', type: 'string', example: 'Governorship Alley 2'),
                                new OA\Property(property: 'postal_code', type: 'string', example: '1234567890'),
                                new OA\Property(property: 'no', type: 'string', example: '12'),
                                new OA\Property(property: 'unit', type: 'string', example: '4'),
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


    public function storeAddress(StoreAddressRequest $request)
    {
        $data = $request->validated();

        $address = $this->addressService->storeAddress($data);

        $address->load([
            'province',
            'city',
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Address successfuly registered.',
            'data' => new AddressResource($address),
        ], 201);
    }


    #[OA\Put(
        path: '/api/update-address/{address}',
        operationId: 'updateAddress',
        tags: ['Address'],
        summary: 'Update user address',
        description: 'Updates an existing address belonging to the authenticated user.',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'address',
                in: 'path',
                required: true,
                description: 'Address ID',
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: [
                    'recipient_name',
                    'city_id',
                    'province_id',
                    'address',
                    'postal_code',
                    'mobile',
                ],
                properties: [
                    new OA\Property(
                        property: 'recipient_name',
                        type: 'string',
                        example: 'abbas jamshidi'
                    ),
                    new OA\Property(
                        property: 'city_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'province_id',
                        type: 'integer',
                        example: 1
                    ),
                    new OA\Property(
                        property: 'address',
                        type: 'string',
                        example: 'tehran valiasr street'
                    ),
                    new OA\Property(
                        property: 'postal_code',
                        type: 'string',
                        example: '1234567890'
                    ),
                    new OA\Property(
                        property: 'no',
                        type: 'string',
                        nullable: true,
                        example: '12'
                    ),
                    new OA\Property(
                        property: 'unit',
                        type: 'string',
                        nullable: true,
                        example: '3'
                    ),
                    new OA\Property(
                        property: 'mobile',
                        type: 'string',
                        example: '09121234567'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Address successfully updated.',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Address successfully updated.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 3),
                                new OA\Property(property: 'recipient_name', type: 'string', example: 'ali soltani'),
                                new OA\Property(property: 'mobile', type: 'string', example: '98765432100'),
                                new OA\Property(
                                    property: 'province',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'Tehran'),
                                    ]
                                ),
                                new OA\Property(
                                    property: 'city',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 3),
                                        new OA\Property(property: 'name', type: 'string', example: 'Shahriar'),
                                    ]
                                ),
                                new OA\Property(property: 'address', type: 'string', example: 'Governorship Alley 2'),
                                new OA\Property(property: 'postal_code', type: 'string', example: '1234567890'),
                                new OA\Property(property: 'no', type: 'string', example: '12'),
                                new OA\Property(property: 'unit', type: 'string', example: '4'),
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
                response: 403,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/403ResponseSchema'
                )
            ),

            new OA\Response(
                response: 404,
                description: 'Address not found',
                content: new OA\JsonContent(ref: '#/components/schemas/404ResponseSchema')
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


    public function updateAddress(UpdateAddressRequest $request, Address $address)
    {
        $data = $request->validated();

        $address = $this->addressService->updateAddress($data, $address);

        return response()->json([
            'status' => 'success',
            'message' => 'Address successfuly updated.',
            'data' => new AddressResource($address),
        ]);
    }


    #[OA\Get(
        path: '/api/provinces/{province}/cities',
        tags: ['Address'],
        security: [['sanctum' => []]],
        summary: 'Get cities by province',
        description: 'Returns all cities that belong to the specified province.',
        parameters: [
            new OA\Parameter(
                name: 'province',
                in: 'path',
                required: true,
                description: 'Province ID',
                schema: new OA\Schema(
                    type: 'integer',
                    example: 1
                )
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of cities retrieved successfully',
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
                            example: 'List of cities in the province'
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                required: ['id', 'name'],
                                properties: [
                                    new OA\Property(
                                        property: 'id',
                                        type: 'integer',
                                        example: 7
                                    ),
                                    new OA\Property(
                                        property: 'name',
                                        type: 'string',
                                        example: 'Shiraz'
                                    ),
                                ]
                            )
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
                description: 'Address not found',
                content: new OA\JsonContent(ref: '#/components/schemas/404ResponseSchema')
            ),
        ]
    )]

    public function getCities(Province $province)
    {
        $cities = $this->addressService->getCities($province);
        return response()->json([
            'status' => 'success',
            'message' => 'List of cities in the province',
            'data' => CityResource::collection($cities),
        ]);
    }
}
