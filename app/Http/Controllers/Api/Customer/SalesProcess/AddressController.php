<?php

namespace App\Http\Controllers\Api\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\DeliveryResource;
use App\Http\Resources\ProvinceResource;
use App\Services\AddressService;
use Illuminate\Http\Request;
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
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(ref: '#/components/schemas/422ResponseSchema')
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

    public function addressAndDelivery(AddressRequest $request)
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
}
