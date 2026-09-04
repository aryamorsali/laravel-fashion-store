<?php

namespace App\Services;

use App\Models\Market\Brand;
use App\Models\Market\Product;


class ProductFilterService
{
    public function productFilter($data, $category = null)
    {
        // دادن مقدارهای پیشفرض به متغیر های موجود
        $data = array_replace([
            'search'       => null,
            'brands'       => [],
            'tag'          => null,
            'colors'       => [],
            'sizes'        => [],
            'min_price'    => null,
            'max_price'    => null,
            'sort'         => 'newness',
            'in_stock'     => 0,
            'out_of_stock' => 0,
            'on_sale'      => 0,
            'big_deals'    => 0,
        ], $data);

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
        if (!empty($data['search'])) {
            $query->where('name', 'LIKE', '%' . $data['search'] . '%');
        }

        // فیلتر برند
        if (!empty($data['brands'])) {
            $brandIds = Brand::whereIn('slug', $data['brands'])->pluck('id');

            $query->whereIn('brand_id', $brandIds);
        }

        // فیلتر تگ

        if (!empty($data['tag'])) {
            $query->whereHas('tags', function ($q) use ($data) {
                $q->where('slug', $data['tag']);
            });
        }

        // فرمول محاسبه قیمت نهایی واریانت در دیتابیس

        $finalPriceSql = $this->finalVariantPriceSql();


        // Eager Loading
        // فقط واریانت‌های منطبق را لود کن
        $this->eagerLoadFilteredVariants(
            $query,
            $data,
            $finalPriceSql
        );

        // حالت خاص: فقط محصولات کاملاً ناموجود

        if ($this->isAbsoluteOutOfStockMode($data)) {

            $query->whereDoesntHave('variants.warehouseVariants', function ($w) {
                $w->whereRaw('(stock - reserved) > 0');
            });
        } else {

            // فیلترهای مربوط به واریانت‌ها

            $query->whereHas('variants', function ($q) use ($data, $finalPriceSql) {
                $this->applyVariantFiltersToEloquentQuery($q, $data, $finalPriceSql);
            });
        }

        // مرتب‌سازی محصولات

        switch ($data['sort']) {

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

                $this->applyDisplayPriceSort($query, $data, $finalPriceSql, 'ASC');

                break;

            case 'most_expensive':

                $this->applyDisplayPriceSort($query, $data, $finalPriceSql, 'DESC');

                break;

            default:
                // مرتب‌ سازی پیش‌ فرض: جدیدترین‌ ها
                $query->orderBy('published_at', 'DESC');

                break;
        }


        $products = $query->paginate(16);

        // انتخاب واریانت نماینده
        foreach ($products as $product) {
            $product->representativeVariant = $this->getRepresentativeVariant($product);
        }


        // تعداد فیلترهای فعال
        $activeFiltersCount = collect([
            !empty($data['colors']),
            !empty($data['sizes']),
            !empty($data['brands']),
            !empty($data['tag']),
            !empty($data['min_price']),
            !empty($data['max_price']),
            $data['in_stock']  == 1,
            $data['out_of_stock'] == 1,
            $data['on_sale']  == 1,
            $data['big_deals']  == 1,
        ])->filter()->count();

        return [
            'products' => $products,
            'activeFiltersCount' => $activeFiltersCount
        ];
    }

    // =====================================================================
    // Helper Methods
    // =====================================================================

    private function applyDisplayPriceSort($query, $data, string $finalPriceSql, string $direction): void
    {
        $query->select('products.*')
            ->selectSub(
                fn($sub) => $this->buildSortSubQuery($sub, $data, $finalPriceSql, 'MIN', true),
                'display_available_price'
            )
            ->selectSub(
                fn($sub) => $this->buildSortSubQuery($sub, $data, $finalPriceSql, 'MIN', false),
                'display_fallback_price'
            )
            ->orderByRaw('COALESCE(display_available_price, display_fallback_price) IS NULL ASC')
            ->orderByRaw("COALESCE(display_available_price, display_fallback_price) {$direction}")
            ->orderBy('published_at', 'DESC');
    }


    //   متد مشترک برای ساخت Subquery قیمت
    //   این متد دقیقاً همان منطق Blade را به SQL تبدیل می‌کند.

    private function buildSortSubQuery($sub, $data, string $finalPriceSql, string $aggregate, bool $onlyInStock): void
    {
        $sub->from('product_variants')
            ->whereColumn('product_variants.product_id', 'products.id');

        $this->applyVariantFiltersToSubQuery($sub, $data, $finalPriceSql);

        if ($onlyInStock) {
            $this->whereVariantIsInStock($sub);
        }

        $sub->selectRaw("$aggregate($finalPriceSql)");
    }


    private function applyVariantFiltersToEloquentQuery($q, $data, string $finalPriceSql): void
    {
        // فیلتر رنگ

        if (!empty($data['colors'])) {
            $q->whereHas('color', function ($c) use ($data) {
                $c->whereIn('slug', $data['colors']);
            });
        }

        // فیلتر سایز

        if (!empty($data['sizes'])) {
            $q->whereHas('size', function ($s) use ($data) {
                $s->whereIn('slug', $data['sizes']);
            });
        }

        // فیلتر بازه قیمت

        if (!empty($data['min_price'])) {
            $q->whereRaw("$finalPriceSql >= ?", [(float) $data['min_price']]);
        }

        if (!empty($data['max_price'])) {
            $q->whereRaw("$finalPriceSql <= ?", [(float) $data['max_price']]);
        }

        // فیلتر موجودی

        if ($data['in_stock'] == 1 && $data['out_of_stock'] != 1) {
            $q->whereHas('warehouseVariants', function ($w) {
                $w->whereRaw('(stock - reserved) > 0');
            });
        }

        if ($data['out_of_stock'] == 1 && $data['in_stock'] != 1) {
            $q->whereDoesntHave('warehouseVariants', function ($w) {
                $w->whereRaw('(stock - reserved) > 0');
            });
        }

        // فیلتر تخفیف

        if ($data['on_sale'] || $data['big_deals']) {
            $q->whereHas('activeAmazingSale', function ($as) use ($data) {

                if ($data['big_deals']) {
                    $as->where('percentage', '>=', 30);
                } else {
                    $as->where('percentage', '>', 0);
                }
            });
        }
    }

    // Query Builder خام
    private function applyVariantFiltersToSubQuery($sub, $data, string $finalPriceSql): void
    {
        if (!empty($data['colors'])) {
            $sub->whereIn('product_variants.color_id', fn($q) => $q->from('product_colors')->select('id')->whereIn('slug', $data['colors']));
        }

        if (!empty($data['sizes'])) {
            $sub->whereIn('product_variants.size_id', fn($q) => $q->from('product_sizes')->select('id')->whereIn('slug', $data['sizes']));
        }

        if (!empty($data['min_price'])) {
            $minPrice = (float) $data['min_price'];
            $sub->whereRaw("$finalPriceSql >= {$minPrice}");
        }

        if (!empty($data['max_price'])) {
            $maxPrice = (float) $data['max_price'];
            $sub->whereRaw("$finalPriceSql <= {$maxPrice}");
        }

        if ($data['in_stock'] == 1 && $data['out_of_stock'] != 1) {
            $this->whereVariantIsInStock($sub);
        }

        if ($data['out_of_stock'] == 1 && $data['in_stock'] != 1) {
            $this->whereVariantIsOutOfStock($sub);
        }

        if ($data['on_sale'] || $data['big_deals']) {
            $sub->whereExists(function ($q) use ($data) {
                $q->from('amazing_sales')->selectRaw('1')
                    ->whereColumn('amazing_sales.product_variant_id', 'product_variants.id')
                    ->where('amazing_sales.is_active', 1)
                    ->where('amazing_sales.start_date', '<=', now())
                    ->where('amazing_sales.end_date', '>=', now());
                if ($data['big_deals']) $q->where('amazing_sales.percentage', '>=', 30);
                else $q->where('amazing_sales.percentage', '>', 0);
            });
        }
    }

    private function whereVariantIsInStock($query): void
    {
        //  برو بگرد ببین اصلا انباری پیدا می‌کنی که
        $query->whereExists(fn($w) => $w->from('warehouse_variants')->selectRaw('1')
            // مربوط به همین واریانت باشه
            ->whereColumn('warehouse_variants.product_variant_id', 'product_variants.id')
            // و تعداد موجودیش بیشتر از صفر باشه؟
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
        return "(product_variants.price - (product_variants.price * IFNULL((SELECT amazing_sales.percentage FROM 
        amazing_sales WHERE amazing_sales.product_variant_id = product_variants.id AND amazing_sales.is_active = 1 AND amazing_sales.start_date <= NOW() AND amazing_sales.end_date >= NOW() LIMIT 1), 0) / 100))";
    }

    private function isAbsoluteOutOfStockMode($data): bool
    {
        return $data['out_of_stock'] == 1 && empty($data['in_stock']) && empty($data['on_sale'])  && empty($data['big_deals']) && empty($data['colors'])  && empty($data['sizes']) && empty($data['min_price']) && empty($data['max_price']);
    }


    private function eagerLoadFilteredVariants($query, array $data, string $finalPriceSql): void
    {
        $query->with([
            'variants' => function ($variantQuery) use ($data, $finalPriceSql) {
                $this->applyVariantFiltersToEloquentQuery(
                    $variantQuery,
                    $data,
                    $finalPriceSql
                );

                $variantQuery->with([
                    'color',
                    'size',
                    'warehouseVariants',
                    'amazingSale',
                    'activeAmazingSale',
                ]);
            },
        ]);
    }



    // انتخاب واریانت نماینده بر اساس فیلتر های اعمال‌ شده

    private function getRepresentativeVariant($product)
    {
        $variants = $product->variants;

        $pool =  $variants;

        if ($pool->isEmpty()) {
            return null;
        }

        // اولویت با موجودهاست، مگر اینکه کاربر فقط out_of_stock زده باشد
        $inStockPool = $pool->filter(fn($v) => $v->availableStock() > 0);

        $pool = $inStockPool->isNotEmpty() ? $inStockPool : $pool;

        $variant = $pool->sortBy('final_price')->first();

        $isVariantAvailable = $variant?->availableStock() > 0;

        $isProductAvailable = $product->variants->sum(fn($v) => $v->availableStock()) > 0;

        return [
            'variant' => $variant,
            'isVariantAvailable' => $isVariantAvailable,
            'isProductAvailable' => $isProductAvailable
        ];
    }

}
