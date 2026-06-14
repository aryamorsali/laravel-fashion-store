<?php

namespace App\Http\Controllers\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Shop\FilteringRequest;
use App\Models\Content\Tag;
use App\Models\Market\Brand;
use App\Models\Market\Product;
use App\Models\Market\ProductCategory;
use App\Models\Market\ProductColor;
use App\Models\Market\ProductSize;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shop(FilteringRequest $request, ?ProductCategory $category)
    {
        // ---------------------------------------------------
        // اطلاعات جانبی صفحه فروشگاه
        // ---------------------------------------------------

        $productCategories = ProductCategory::whereNull('parent_id')->get();

        $brands = Brand::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $tags = Tag::orderBy('created_at', 'desc')->get();

        $colors = ProductColor::all();

        $sizes = ProductSize::all();

        // ولیدیشن ورودی‌ها

        $request->validated();

        // کوئری اصلی محصولات

        $query = Product::query()
            ->where('status', 'published');

        // فیلتر دسته‌بندی

        if ($category && $category->id) {
            // نشان دادن دسته با زیر دسته
            $categoryIds = $category->children()->pluck('id')->push($category->id);
            $query->whereIn('category_id', $categoryIds);
        }

        // جستجو بر اساس نام محصول

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // فیلتر برند

        if ($request->filled('brands')) {
            $brandIds = Brand::whereIn('slug', $request->brands)->pluck('id');

            $query->whereIn('brand_id', $brandIds);
        }

        // فیلتر تگ

        if ($request->tag) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        // فرمول محاسبه قیمت نهایی واریانت در دیتابیس

        $finalPriceSql = $this->finalVariantPriceSql();

        // حالت خاص: فقط محصولات کاملاً ناموجود

        if ($this->isAbsoluteOutOfStockMode($request)) {

            $query->whereDoesntHave('variants.warehouseVariants', function ($w) {
                $w->whereRaw('(stock - reserved) > 0');
            });
        } else {

            // فیلترهای مربوط به واریانت‌ها

            $query->whereHas('variants', function ($q) use ($request, $finalPriceSql) {
                $this->applyVariantFiltersToEloquentQuery($q, $request, $finalPriceSql);
            });
        }

        // Eager Loading
        $query->with([
            'variants.color',
            'variants.size',
            'variants.warehouseVariants',
            'variants.amazingSale',
            'variants.activeAmazingSale',
        ]);

        // مرتب‌سازی محصولات

        switch ($request->sort) {

            case 'trending':

                // محصولاتی که در 30 روز اخیر بیشترین فروش را داشته‌اند

                $validOrders = function ($q) {
                    $q->where('payment_status', 'paid')
                        ->whereNotIn('order_status', ['canceled', 'returned'])
                        ->where('created_at', '>=', now()->subDays(30));
                };

                $query->withSum(['orderItems as total_sold' => function ($q) use ($validOrders) {
                    $q->whereHas('order', $validOrders);
                }], 'quantity')
                    ->orderByRaw('COALESCE(total_sold, 0) DESC')
                    ->orderBy('published_at', 'DESC');

                break;

            case 'best_selling':

                // محصولاتی که در کل بیشترین فروش را داشته‌اند

                $validOrders = function ($q) {
                    $q->where('payment_status', 'paid')
                        ->whereNotIn('order_status', ['canceled', 'returned']);
                };

                $query->withSum(['orderItems as total_sold' => function ($q) use ($validOrders) {
                    $q->whereHas('order', $validOrders);
                }], 'quantity')
                    ->orderByRaw('COALESCE(total_sold, 0) DESC')
                    ->orderBy('published_at', 'DESC');

                break;

            case 'top_rated':

                // مرتب‌سازی بر اساس میانگین امتیاز کامنت‌های تاییدشده

                $query->withAvg(['comments as avg_rating' => function ($q) {
                    $q->where('approved', 1);
                }], 'rating')
                    ->orderByRaw('COALESCE(avg_rating, 0) DESC')
                    ->orderBy('published_at', 'DESC');

                break;

            case 'newness':

                // جدیدترین محصولات

                $query->orderBy('published_at', 'DESC');

                break;

            case 'cheapest':

                $this->applyDisplayPriceSort($query, $request, $finalPriceSql, 'ASC');

                break;

            case 'most_expensive':

                $this->applyDisplayPriceSort($query, $request, $finalPriceSql, 'DESC');

                break;

            default:
                // مرتب‌سازی پیش‌فرض: جدیدترین‌ها
                $query->orderBy('published_at', 'DESC');

                break;
        }


        $products = $query->paginate(16);

        // تعداد فیلترهای فعال
        $activeFiltersCount = collect([
            $request->filled('colors'),
            $request->filled('sizes'),
            $request->filled('brands'),
            $request->filled('tag'),
            $request->filled('min_price'),
            $request->filled('max_price'),
            $request->in_stock == 1,
            $request->out_of_stock == 1,
            $request->on_sale == 1,
            $request->big_deals == 1,
        ])->filter()->count();

        return view('customer.market.shop', compact(
            'products',
            'brands',
            'productCategories',
            'colors',
            'sizes',
            'tags',
            'activeFiltersCount'
        ));
    }

    // =====================================================================
    // Helper Methods
    // =====================================================================

    private function applyDisplayPriceSort($query, FilteringRequest $request, string $finalPriceSql, string $direction): void
    {
        $query->select('products.*')
            ->selectSub(
                fn($sub) => $this->buildSortSubQuery($sub, $request, $finalPriceSql, 'MIN', true),
                'display_available_price'
            )
            ->selectSub(
                fn($sub) => $this->buildSortSubQuery($sub, $request, $finalPriceSql, 'MIN', false),
                'display_fallback_price'
            )
            ->orderByRaw('COALESCE(display_available_price, display_fallback_price) IS NULL ASC')
            ->orderByRaw("COALESCE(display_available_price, display_fallback_price) {$direction}")
            ->orderBy('published_at', 'DESC');
    }

    
    //   متد مشترک برای ساخت Subquery قیمت
    //   این متد دقیقاً همان منطق Blade را به SQL تبدیل می‌کند.
     
    private function buildSortSubQuery($sub, FilteringRequest $request, string $finalPriceSql, string $aggregate, bool $onlyInStock): void
    {
        $sub->from('product_variants')
            ->whereColumn('product_variants.product_id', 'products.id');

        $this->applyVariantFiltersToSubQuery($sub, $request, $finalPriceSql);

        if ($onlyInStock) {
            $this->whereVariantIsInStock($sub);
        }

        $sub->selectRaw("$aggregate($finalPriceSql)");
    }


    private function applyVariantFiltersToEloquentQuery($q, FilteringRequest $request, string $finalPriceSql): void
    {
        // فیلتر رنگ

        if ($request->filled('colors')) {
            $q->whereHas('color', function ($c) use ($request) {
                $c->whereIn('slug', $request->colors);
            });
        }

        // فیلتر سایز

        if ($request->filled('sizes')) {
            $q->whereHas('size', function ($s) use ($request) {
                $s->whereIn('slug', $request->sizes);
            });
        }

        // فیلتر بازه قیمت

        if ($request->filled('min_price')) {
            $q->whereRaw("$finalPriceSql >= ?", [(float) $request->min_price]);
        }

        if ($request->filled('max_price')) {
            $q->whereRaw("$finalPriceSql <= ?", [(float) $request->max_price]);
        }

        // فیلتر موجودی

        if ($request->in_stock == 1 && $request->out_of_stock != 1) {
            $q->whereHas('warehouseVariants', function ($w) {
                $w->whereRaw('(stock - reserved) > 0');
            });
        }

        if ($request->out_of_stock == 1 && $request->in_stock != 1) {
            $q->whereDoesntHave('warehouseVariants', function ($w) {
                $w->whereRaw('(stock - reserved) > 0');
            });
        }

        // فیلتر تخفیف

        if ($request->on_sale || $request->big_deals) {
            $q->whereHas('activeAmazingSale', function ($as) use ($request) {

                if ($request->big_deals) {
                    $as->where('percentage', '>=', 30);
                } else {
                    $as->where('percentage', '>', 0);
                }
            });
        }
    }


    private function applyVariantFiltersToSubQuery($sub, FilteringRequest $request, string $finalPriceSql): void
    {
        if ($request->filled('colors')) {
            $sub->whereIn('product_variants.color_id', fn($q) => $q->from('product_colors')->select('id')->whereIn('slug', $request->colors));
        }

        if ($request->filled('sizes')) {
            $sub->whereIn('product_variants.size_id', fn($q) => $q->from('product_sizes')->select('id')->whereIn('slug', $request->sizes));
        }

        if ($request->filled('min_price')) {
            $sub->whereRaw("$finalPriceSql >= ?", [(float) $request->min_price]);
        }

        if ($request->filled('max_price')) {
            $sub->whereRaw("$finalPriceSql <= ?", [(float) $request->max_price]);
        }

        if ($request->in_stock == 1 && $request->out_of_stock != 1) {
            $this->whereVariantIsInStock($sub);
        }

        if ($request->out_of_stock == 1 && $request->in_stock != 1) {
            $this->whereVariantIsOutOfStock($sub);
        }

        if ($request->on_sale || $request->big_deals) {
            $sub->whereExists(function ($q) use ($request) {
                $q->from('amazing_sales')->selectRaw('1')
                    ->whereColumn('amazing_sales.product_variant_id', 'product_variants.id')
                    ->where('amazing_sales.is_active', 1);
                if ($request->big_deals) $q->where('amazing_sales.percentage', '>=', 30);
                else $q->where('amazing_sales.percentage', '>', 0);
            });
        }
    }

    private function whereVariantIsInStock($query): void
    {
        $query->whereExists(fn($w) => $w->from('warehouse_variants')->selectRaw('1')
            ->whereColumn('warehouse_variants.product_variant_id', 'product_variants.id')
            ->whereRaw('(warehouse_variants.stock - warehouse_variants.reserved) > 0'));
    }

    private function whereVariantIsOutOfStock($query): void
    {
        $query->whereNotExists(fn($w) => $w->from('warehouse_variants')->selectRaw('1')
            ->whereColumn('warehouse_variants.product_variant_id', 'product_variants.id')
            ->whereRaw('(warehouse_variants.stock - warehouse_variants.reserved) > 0'));
    }

    private function finalVariantPriceSql(): string
    {
        return "(product_variants.price - (product_variants.price * IFNULL((SELECT amazing_sales.percentage FROM amazing_sales WHERE amazing_sales.product_variant_id = product_variants.id AND amazing_sales.is_active = 1 LIMIT 1), 0) / 100))";
    }

    private function isAbsoluteOutOfStockMode(FilteringRequest $request): bool
    {
        return $request->out_of_stock == 1 && !$request->in_stock && !$request->on_sale && !$request->big_deals && !$request->filled('colors') && !$request->filled('sizes') && !$request->filled('min_price') && !$request->filled('max_price');
    }
}
