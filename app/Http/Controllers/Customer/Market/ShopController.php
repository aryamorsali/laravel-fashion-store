<?php

namespace App\Http\Controllers\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Shop\FilteringRequest;
use App\Models\Market\ProductCategory;
use App\Services\ProductFilterService;
use App\Models\Market\ProductColor;
use App\Models\Market\ProductSize;
use App\Models\Content\Tag;
use App\Models\Market\Brand;


class ShopController extends Controller
{
    public function shop(FilteringRequest $request, ?ProductCategory $category, ProductFilterService $productFilterService)
    {
        $data = $request->validated();

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


        $result = $productFilterService->productFilter($data, $category ?? null);

        $products = $result['products'];
        $activeFiltersCount = $result['activeFiltersCount'];

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
    
}
