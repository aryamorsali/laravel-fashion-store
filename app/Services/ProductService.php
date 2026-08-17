<?php


namespace App\Services;

use Carbon\Carbon;
use App\Models\Market\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Content\Comment;

class ProductService
{
    public function productDetail($product, $request)
    {
        abort_if(
            $product->status !== 'published',
            404
        );
        $product = Product::withTotalSold()
            ->with([
                'productCategory' => fn($q) => $q->where('status', 1),
                'brand' => fn($q) => $q->where('status', 1),
                'images',
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
                    'price' =>(float) $v->price,
                    'final_price' => (float) $v->final_price,
                    'stock' => (int) $v->availableStock(),
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

        return [
            'product' => $product,
            'variantsForJs' => $variantsForJs,
            'hasSellableVariant' => $hasSellableVariant,
            'approvedComments' => $approvedComments,
            'relatedProducts' => $relatedProducts,
            'aveRating' => $aveRating,
            'selectedVariantId' => $selectedVariantId
        ];
    }


    public function addComment($product, $data)
    {

        return Comment::create([
            'body' => $data['body'],
            'parent_id' => null,
            'author_id' => Auth::id(),
            'commentable_type' => Product::class,
            'commentable_id' => $product->id,
            'rating' => $data['rating'],
        ]);
    }
}
