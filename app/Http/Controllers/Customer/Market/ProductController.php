<?php

namespace App\Http\Controllers\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Product\CommentRequest;
use App\Models\Content\Comment;
use App\Models\Market\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function product(Product $product, Request $request)
    {
        abort_if(
            $product->status !== 'published',
            404
        );
        $product = Product::withTotalSold()
            ->with([
                'attributeValues.productAttribute',
                'variants.activeAmazingSale',
                'variants.color',
                'variants.size',
            ])->whereKey($product->getKey())->firstOrFail();

        $variantsForJs = $product->variants
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'color_id' => $v->color?->id,
                    'color_name' => $v->color?->name,
                    'color_hex' => $v->color?->hex_code,
                    'size_id' => $v->size?->id,
                    'size_name' => $v->size?->name,
                    'price' => $v->price,
                    'stock' => $v->availableStock(),
                    'percentage' => $v->activeAmazingSale?->percentage,
                    'expire_at' => $v->activeAmazingSale?->end_date ? Carbon::parse($v->activeAmazingSale->end_date)->toIso8601String() : null,
                ];
            })->values()->toArray();


        // پیدا کردن واریانتی ک کاربر دیده است
        $requestedVariantId = $request->query('variant');

        $selectedVariant = null;

        if ($requestedVariantId) {
            $selectedVariant = $product->variants
                ->firstWhere('id', (int) $requestedVariantId);
        }

        if (!$selectedVariant || $selectedVariant->availableStock() <= 0) {
            $selectedVariant = $product->variants
                ->first(fn($v) => $v->availableStock() > 0);
        }

        $selectedVariantId = $selectedVariant?->id;

        // dd($selectedVariantId);

        // محصول در کل موجود هست یا نه؟
        $hasSellableVariant = $product->variants->contains(fn($v) => $v->availableStock() > 0);

        // کامنت های تایید شده
        $approvedComments = $product->activeComments()
            ->with(['user', 'children.user'])
            ->latest()
            ->paginate(5);

        // محصولات مرتبط با محصول فعلی
        $relatedProducts = Product::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)

            // فقط محصولاتی که حداقل یک واریانت موجود دارند
            ->whereHas('variants', function ($q) {
                $q->whereHas('warehouseVariants', function ($wq) {
                    $wq->whereColumn('stock', '>', 'reserved');
                });
            })
            ->with([
                'variants' => function ($q) {
                    $q->with([
                        // موجودی
                        'warehouseVariants',

                        // تخفیف فعال
                        'amazingSale' => function ($s) {
                            $s->where('is_active', true)
                                ->where('start_date', '<=', now())
                                ->where('end_date', '>=', now());
                        },
                        'orderItems',
                    ]);
                }
            ])->inRandomOrder()
            ->take(8)
            ->get();

        // میانگین امتیاز محصول
        $aveRating = $product->activeComments->avg('rating') ?? 0;

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
        if (!Auth::user()) {
            return redirect()->back()->with(
                'toast-success',
                'Sign in to leave a review. 
                 <a href="' . route('auth.login-register.form') . '" class="toast-link">Login / Register</a>'
            );
        }
        $data  = $request->validated();

        Comment::create([
            'body' => $data['body'],
            'parent_id' => null,
            'author_id' => Auth::user()->id,
            'commentable_type' => Product::class,
            'commentable_id' => $product->id,
            'rating' => $data['rating'],
        ]);
        return redirect()->back()->with(
            'toast-success',
            'Thanks for your review! It’s pending admin approval.'
        );
    }
}
