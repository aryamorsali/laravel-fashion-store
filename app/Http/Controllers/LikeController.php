<?php

namespace App\Http\Controllers;

use App\Models\Market\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Like')]

class LikeController extends Controller
{


    #[OA\Post(
        path: '/api/like/{type}/{id}',
        summary: 'Toggle like/wishlist',
        tags: ['Like'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(
                name: 'type',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', enum: ['product'], example: 'product')

            ),
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'liked', type: 'boolean'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Invalid like type'),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/401ResponseSchema'
                )
            ),
            new OA\Response(response: 404, description: 'Resource not found'),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'The type field is required. | The id field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            description: 'Dictionary of field errors',
                            properties: [
                                new OA\Property(
                                    property: 'type',
                                    type: 'string',
                                    example: "The type field is required."
                                ),
                                new OA\Property(
                                    property: 'id',
                                    type: 'string',
                                    example: "The id field is required."
                                ),

                            ]
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


    public function toggle(Request $request, string $type, int $id)
    {

        if ($type !== 'product') {
            abort(400, 'Invalid like type');
        }

        $model = Product::findOrFail($id);


        $existing = $model->likes()->where('user_id', Auth::id())->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
            return response()->json([
                'status' => 'success',
                'message' => 'Product successfully removed from wishlist.',
                'liked' => $liked
            ]);
        } else {
            $model->likes()->create(['user_id' => Auth::id()]);
            $liked = true;
            return response()->json([
                'status' => 'success',
                'message' => "Product successfully added to wishlist.",
                'liked' => $liked
            ]);
        }
    }
}
