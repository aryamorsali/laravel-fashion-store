<?php

namespace App\Http\Controllers\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\CommentRequest;
use App\Models\Content\Comment;
use App\Models\Market\Product;
use App\Models\User;
use App\Notifications\NewProductCommentRegisteredNotification;
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

        $comment = $this->productService->addComment($product, $data);

        // new product comment notification
        $admins = User::where('activation', 1)->get()->filter(function ($u) {
            return $u->is_owner || $u->hasPermissionTo('manage-product-comments');
        });

        foreach ($admins as $admin) {
            $admin->notify(new NewProductCommentRegisteredNotification($comment));
        }

        return redirect()->back()->with(
            'toast-success',
            'Thanks for your review! It’s pending admin approval.'
        );
    }
}
