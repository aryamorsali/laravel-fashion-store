<?php

namespace App\Http\Controllers\Api\Customer\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\FilteringRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Market\ProductCategory;
use App\Services\ProductFilterService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shop(FilteringRequest $request, ?ProductCategory $category, ProductFilterService $productFilterService)
    {
        $data = $request->validated();

        $result = $productFilterService->productFilter($data, $category ?? null);
        $paginator = $result['products'];

        return response()->json([
            'status' => 'success',
            'message' => 'Products were successfully found.',
            'data' => [
                'products' => ProductListResource::collection($paginator->items()),
                'active_filters_count' => $result['activeFiltersCount'],
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),         
                    'total' => $paginator->total(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'prev_page_url' => $paginator->previousPageUrl(),
                ],
            ],
        ]);
    }
}
