<?php

namespace App\Http\Controllers\Customer\Market;

use App\Http\Controllers\Controller;
use App\Models\Market\Product;
use App\Models\Market\ProductSize;
use App\Models\Market\WarehouseVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product(Product $product)
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
            ])
            ->whereKey($product->getKey())
            ->firstOrFail();


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
                ];
            })
            ->values()
            ->toArray();

        // محصول در کل موجود هست یا نه؟
        $hasSellableVariant = $product->variants->contains(fn($v) => $v->availableStock() > 0);


        // dd($variantsForJs);

        return view('customer.market.product-details', compact(
            'product',
            'variantsForJs',
            'hasSellableVariant'
        ));
    }
}
