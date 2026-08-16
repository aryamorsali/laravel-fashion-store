<?php

namespace App\Http\Controllers\Api\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ProductResource;
use App\Models\Market\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

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
                'approvedComments' => $result['approvedComments'],
                'relatedProducts' => $result['relatedProducts'],
                'aveRating' => $result['aveRating'],
                'selectedVariantId' => $result['selectedVariantId'],
            ]
        ]);
    }



    public function addComment(Product $product, CommentRequest $request)
    {

        $data  = $request->validated();

        $result = $this->productService->addComment($product, $data);


        return response()->json([
            'status' => 'success',
            'message' => 'Your comment has been recorded and will be displayed after review.',
            'data' => [
                'comment' => new CommentResource($comment),
            ],
        ]);
    }
}
