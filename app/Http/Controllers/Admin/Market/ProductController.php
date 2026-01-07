<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Market\Brand;
use App\Models\Market\Product;
use App\Models\Market\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
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

        Product::create($inputs);
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
