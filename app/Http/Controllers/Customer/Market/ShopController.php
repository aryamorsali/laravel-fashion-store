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
        // get categories
        $productCategories = ProductCategory::whereNull('parent_id')->get();

        // get brands
        $brands = Brand::where('status', 1)->orderBy('created_at', 'desc')->get();

        // get tags
        $tags = Tag::orderBy('created_at', 'desc')->get();

        // get product colors
        $colors = ProductColor::all();

        // get product sizes
        $sizes = ProductSize::all();



        //  ولیدیشن ورودی‌ها
        $request->validated();

        // query
        $query = Product::query()->where('status', 'published');


        // category selection
        if ($category && $category->id) {
            $query->where('category_id', $category->id);
        }

        // search
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // brands
        if ($request->filled('brands')) {
            $brandIds = Brand::whereIn('slug', $request->brands)->pluck('id');
            $query->whereIn('brand_id', $brandIds);
        }

        // colors & sizes together
        if ($request->filled('colors') || $request->filled('sizes')) {

            $query->whereHas('variants', function ($q) use ($request) {

                // filter by colors
                if ($request->filled('colors')) {

                    $q->whereHas('color', function ($colorQuery) use ($request) {

                        $colorQuery->whereIn('slug', $request->colors);
                    });
                }

                // filter by sizes
                if ($request->filled('sizes')) {

                    $q->whereHas('size', function ($sizeQuery) use ($request) {

                        $sizeQuery->whereIn('slug', $request->sizes);
                    });
                }
            });
        }


        $query->with([
            'variants.color',
            'variants.size',
            'variants.warehouseVariants',
            'variants.amazingSale',
            'variants.activeAmazingSale'
        ]);


        // min, max price
        if ($request->filled('min_price') || $request->filled('max_price')) {

            $query->whereHas('variants', function ($q) use ($request) {

                if ($request->filled('min_price')) {
                    $q->where('price', '>=', $request->min_price);
                }

                if ($request->filled('max_price')) {
                    $q->where('price', '<=', $request->max_price);
                }
            });
        }



        // stock
        if ($request->in_stock xor $request->out_of_stock) {

            // فقط موجود
            if ($request->in_stock == 1) {
                $query->whereHas('variants.warehouseVariants', function ($q) {
                    $q->whereRaw('(stock - reserved) > 0');
                });
            }

            // فقط ناموجود
            if ($request->out_of_stock == 1) {
                $query->whereDoesntHave('variants.warehouseVariants', function ($q) {
                    $q->whereRaw('(stock - reserved) > 0');
                });
            }
        }


        // discount filters
        if ($request->on_sale xor $request->big_deals) {

            // فقط محصولات دارای هرگونه تخفیف
            if ($request->on_sale == 1) {
                $query->whereHas('variants.activeAmazingSale');
            }

            // فقط تخفیف‌های بزرگ (50%+‌)
            if ($request->big_deals == 1) {
                $query->whereHas('variants.activeAmazingSale', function ($q) {
                    $q->where('percentage', '>=', 50);
                });
            }

            // اگر هر دو تیک خورده باشن یعنی کاربر می‌خواد همه‌ی محصولات تخفیف‌دار رو ببینه
        } elseif ($request->on_sale && $request->big_deals) {

            $query->whereHas('variants.activeAmazingSale');
        }


        // tag
        if ($request->tag) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        // switch for set sorts
        switch ($request->sort) {

            case 'trending':

                $validOrders = function ($q) {
                    $q->where('payment_status', 'paid')
                        ->whereNotIn('order_status', ['canceled', 'returned'])
                        ->where('created_at', '>=', now()->subDays(30));
                };
                $query->withSum(['orderItems as total_sold' => function ($q) use ($validOrders) {
                    $q->whereHas('order', $validOrders);
                }], 'quantity')

                    // مرتب کردن براساس total_sold
                    ->orderByRaw('COALESCE(total_sold,0) DESC');

                // ->orderByDesc('variants.warehouseVariants.sold')               میشه از این هم استفاده کرد

                break;

            case 'best_selling':

                $validOrders = function ($q) {
                    $q->where('payment_status', 'paid')
                        ->whereNotIn('order_status', ['canceled', 'returned']);
                };
                $query->withSum(['orderItems as total_sold' => function ($q) use ($validOrders) {
                    $q->whereHas('order', $validOrders);
                }], 'quantity')

                    // مرتب کردن براساس total_sold
                    ->orderByRaw('COALESCE(total_sold,0) DESC');

                // ->orderByDesc('variants.warehouseVariants.sold')               میشه از این هم استفاده کرد

                break;

            case 'top_rated':

                $query->withAvg(['comments as avg_rating' => function ($q) {
                    $q->where('approved', 1);
                }], 'rating')
                    ->orderByRaw('COALESCE(avg_rating,0) DESC');
                break;

            case 'newness':

                $query->orderBy('published_at', 'DESC');
                break;

            case 'cheapest':

                $query->withMin('variants as min_price', 'price')
                    ->orderBy('min_price', 'ASC');
                break;

            case 'most_expensive':

                $query->withMax('variants as max_price', 'price')
                    ->orderBy('max_price', 'DESC');
                break;

            default:
                $query->orderBy('published_at', 'DESC');
        }


        $products = $query->paginate(16);

        return view('customer.market.shop', compact('products', 'brands', 'productCategories', 'colors', 'sizes', 'tags'));
    }
}
