<?php

namespace App\Http\Controllers\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\CommentRequest;
use App\Models\Content\Comment;
use App\Models\Market\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


        $product = $result['product'];
        $variantsForJs = $result['variantsForJs'];
        $hasSellableVariant = $result['hasSellableVariant'];
        $approvedComments = $result['approvedComments'];
        $relatedProducts = $result['relatedProducts'];
        $aveRating = $result['aveRating'];
        $selectedVariantId = $result['selectedVariantId'];


        return view('customer.market.product-details', compact(
            'product',
            'variantsForJs',
            'hasSellableVariant',
            'approvedComments',
            'relatedProducts',
            'aveRating',
            'selectedVariantId'
        ));
    }

    public function addComment(Product $product, CommentRequest $request)
    {

        $data  = $request->validated();

        $result = $this->productService->addComment($product, $data);


        return redirect()->back()->with(
            'toast-success',
            'Thanks for your review! It’s pending admin approval.'
        );
    }
}
