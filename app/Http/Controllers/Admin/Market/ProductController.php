<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Market\Brand;
use App\Models\Market\Product;
use App\Models\Market\ProductCategory;
use App\Models\Market\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.market.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = ProductCategory::with('children')->whereNull('parent_id')->get();
        $brands = Brand::all();
        return view('admin.market.product.create', compact('productCategories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request, ImageService $imageService)
    {

        $inputs = $request->validated();

        $inputs['has_color'] = (int) $request->has_color;
        $inputs['has_size']  = (int) $request->has_size;

        if (empty($inputs['published_at'])) {
            $inputs['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $inputs['published_at'] = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at'])->format('Y-m-d H:i:s');
        }

        if ($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'product');
            $result = $imageService->createIndexAndSave($request->file('image'));

            if ($result === false) {
                return redirect()->route('admin.market.product.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        }

        $product = Product::create($inputs);

        if ($product->has_color == 0 && $product->has_size == 0) {
            ProductVariant::create([
                'product_id' => $product->id,
                'color_id' => null,
                'size_id' => null,
                'price' => $product->base_price,
            ]);
        } else {
            return redirect()->route('admin.market.variant.create', $product)->with(
                'alert-section-warning',
                'Please create product variants.'
            );
        }



        return redirect()->route('admin.market.product.index')->with(
            'alert-section-success',
            'Your new product was successfully registered.'
        );
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
    public function edit(Product $product)
    {
        $productCategories = ProductCategory::with('children')->whereNull('parent_id')->get();
        $brands = Brand::all();
        return view('admin.market.product.edit', compact('productCategories', 'brands', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product, ImageService $imageService)
    {
        $inputs = $request->validated();


        $inputs['has_color'] = (int) $request->has_color;
        $inputs['has_size']  = (int) $request->has_size;

        if (empty($inputs['published_at'])) {
            $inputs['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $inputs['published_at'] = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at'])->format('Y-m-d H:i:s');
        }

        if ($request->hasFile('image')) {
            if (!empty($product->image)) {
                $imageService->deleteIndexFiles($product->image['indexArray']);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'product');
            $result = $imageService->createIndexAndSave($request->file('image'));
            if ($result === false) {
                return redirect()->route('admin.market.product.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        } else {
            if (isset($inputs['currentImage']) && !empty($product->image)) {
                $image = $product->image;
                $image['currentImage'] = $inputs['currentImage'];
                $inputs['image'] = $image;
            }
        }
        $product->update($inputs);

        if ($product->has_color == 0 && $product->has_size == 0 && $product->variants()->count() == 0) {
            ProductVariant::create([
                'product_id' => $product->id,
                'color_id' => null,
                'size_id' => null,
                'price' => $product->base_price,
            ]);
            return redirect(route('admin.market.product.index'))->with(
                'alert-section-success',
                'Product editing completed successfully.'
            );
        }
        if (($product->has_color || $product->has_size) && $product->variants()->count() == 0) {
            $product->update(['status' => 'draft']);
        }

        // حذف واریانت سیستمی در صورت تغییر ماهیت
        if ($product->wasChanged(['has_color', 'has_size'])) {
            $product->variants()
                ->whereNull('color_id')
                ->whereNull('size_id')
                ->delete();

            if ($product->has_color == 0 && $product->has_size == 0) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => null,
                    'size_id' => null,
                    'price' => $product->base_price,
                ]);
                return redirect(route('admin.market.product.index'))->with(
                    'alert-section-success',
                    'Product editing completed successfully.'
                );
            }

            return redirect()->route('admin.market.variant.create', $product)->with(
                'alert-section-warning',
                'The nature of the product has changed. Please create new product variants.'
            );
        }

        return redirect(route('admin.market.product.index'))->with(
            'alert-section-success',
            'Product editing completed successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect(route('admin.market.product.index'))->with(
            'alert-section-success',
            'Product successfully deleted.'
        );
    }
}
