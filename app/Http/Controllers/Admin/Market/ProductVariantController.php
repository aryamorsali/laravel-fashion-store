<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductVariantRequest;
use App\Models\Market\Product;
use App\Models\Market\ProductColor;
use App\Models\Market\ProductSize;
use App\Models\Market\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        return view('admin.market.product.variant.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Product $product)
    {
        if (!$product->has_color && !$product->has_size) {
            return redirect()->route('admin.market.variant.index', $product)
                ->with('alert-section-warning', 'This product is simple. To have a different variant, first change the product nature to variable.');
        }
        $colors = ProductColor::all();
        $sizes = ProductSize::all();
        return view('admin.market.product.variant.create', compact('product', 'colors', 'sizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductVariantRequest $request, Product $product)
    {
        if (!$product->has_color && !$product->has_size) {
            abort(403, 'This product is simple and does not allow for adding new variants.');
        }

        $colors = $request->colors;
        $sizes = $request->sizes ?? [null];
        $allColors = ProductColor::pluck('name', 'id')->toArray();
        $allSizes  = ProductSize::pluck('name', 'id')->toArray();
        $duplicates = []; // ذخیره ترکیب‌های تکراری

        if ($colors != null) {
            foreach ($colors as $colorId) {
                foreach ($sizes as $sizeId) {

                    $exists = ProductVariant::where([
                        'product_id' => $product->id,
                        'color_id' => $colorId,
                        'size_id' => $sizeId,
                    ])->exists();

                    // اگر واریانت از قبل وجود نداشت
                    if (!$exists) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color_id' => $colorId,
                            'size_id' => $sizeId,
                            'price' => $request->price,
                            'stock' => $request->stock,
                        ]);
                    } else {
                        $colorName = $allColors[$colorId] ?? 'Unknown color';
                        $sizeName  = $allSizes[$sizeId] ?? 'No size';
                        $duplicates[] = $colorName . ' / ' . $sizeName;
                    }
                }
            }
        } else {
            foreach ($sizes as $sizeId) {

                $exists = ProductVariant::where([
                    'product_id' => $product->id,
                    'color_id' => null,
                    'size_id' => $sizeId,
                ])->exists();

                // اگر واریانت از قبل وجود نداشت
                if (!$exists) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => null,
                        'size_id' => $sizeId,
                        'price' => $request->price,
                        'stock' => $request->stock,
                    ]);
                } else {
                    $sizeName  = $allSizes[$sizeId] ?? 'No size';
                    $duplicates[] = $sizeName;
                }
            }
        }


        if (!empty($duplicates)) {
            return redirect()->back()->with(
                'alert-section-warning',
                'Some variants were previously registered: ' . implode(' , ', $duplicates)
            );
        }
        return redirect()->route('admin.market.variant.index', $product)
            ->with('alert-section-success', 'Variants created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, ProductVariant $variant)
    {
        return view('admin.market.product.variant.edit', compact('product', 'variant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductVariantRequest $request, Product $product, ProductVariant $variant)
    {
        $variant->update([
            'price' => $request->price,
        ]);

        return redirect()->route('admin.market.variant.index', $product)
            ->with('alert-section-success', 'Variant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductVariant $variant)
    {
        if ($variant->product_id != $product->id) {
            abort(404);
        }
        $variant->delete();
        if ($product->variants()->doesntExist()) {
            $product->updateQuietly([
                'status' => 'draft',
            ]);
            return redirect()->route('admin.market.variant.index', $product)->with(
                'alert-section-warning',
                'Due to the removal of all variants, the product was reverted to draft status.'
            );
        }

        return redirect()->back()->with(
            'alert-section-success',
            'Variant successfully removed.'
        );
    }

    public function destroyAllVariants(Product $product)
    {
        if ($product->variants->isNotEmpty()) {
            $product->variants()->delete();
            if ($product->variants()->doesntExist()) {
                $product->updateQuietly([
                    'status' => 'draft',
                ]);
                return redirect()->back()->with(
                    'alert-section-warning',
                    'Due to the removal of all variants, the product was reverted to draft status.'
                );
            }
            return redirect()->back()->with(
                'alert-section-success',
                'Variants successfully removed.'
            );
        }
    }
}
