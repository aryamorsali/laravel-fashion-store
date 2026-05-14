<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Content\Tag;
use App\Models\Market\Brand;
use App\Models\Market\Product;
use App\Models\Market\ProductCategory;
use App\Models\Market\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $tags = Tag::all();

        $productCategories = ProductCategory::with('children')->whereNull('parent_id')->get();
        $brands = Brand::all();
        return view('admin.market.product.create', compact('productCategories', 'brands', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request, ImageService $imageService)
    {

        $inputs = $request->validated();

        $tags = $inputs['tags'] ?? null;

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

        $product = DB::transaction(function () use ($inputs, $tags) {

            $product = Product::create($inputs);
            // attach tags

            if ($tags) {
                $product->tags()->sync($tags);
            }

            if ($product->has_color == 0 && $product->has_size == 0) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => null,
                    'size_id' => null,
                    'price' => $product->base_price,
                ]);
            }
            return $product;
        });

        if ($product->has_color || $product->has_size) {
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
        $tags = Tag::all();

        $productCategories = ProductCategory::with('children')->whereNull('parent_id')->get();
        $brands = Brand::all();
        return view('admin.market.product.edit', compact('productCategories', 'brands', 'product', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product, ImageService $imageService)
    {
        $inputs = $request->validated();

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

        //     DB::transaction(function () use ($inputs, $tags, $product) {

        //         $product->update($inputs);

        //         // تگ‌ها (sync یا detach)
        //         if (!empty($tags)) {
        //             $product->tags()->sync($tags);
        //         } else {
        //             $product->tags()->detach();
        //         }

        //         if ($product->has_color == 0 && $product->has_size == 0 && $product->variants()->count() == 0) {
        //             ProductVariant::create([
        //                 'product_id' => $product->id,
        //                 'color_id' => null,
        //                 'size_id' => null,
        //                 'price' => $product->base_price,
        //             ]);
        //             return redirect(route('admin.market.product.index'))->with(
        //                 'alert-section-success',
        //                 'Product editing completed successfully.'
        //             );
        //         }
        //         if (($product->has_color || $product->has_size) && $product->variants()->count() == 0) {
        //             $product->update(['status' => 'draft']);
        //         }

        //         // حذف واریانت سیستمی در صورت تغییر ماهیت
        //         if ($product->wasChanged(['has_color', 'has_size'])) {
        //             $product->variants()
        //                 ->whereNull('color_id')
        //                 ->whereNull('size_id')
        //                 ->delete();

        //             if ($product->has_color == 0 && $product->has_size == 0) {
        //                 ProductVariant::create([
        //                     'product_id' => $product->id,
        //                     'color_id' => null,
        //                     'size_id' => null,
        //                     'price' => $product->base_price,
        //                 ]);
        //                 return redirect(route('admin.market.product.index'))->with(
        //                     'alert-section-success',
        //                     'Product editing completed successfully.'
        //                 );
        //             }

        //             return redirect()->route('admin.market.variant.create', $product)->with(
        //                 'alert-section-warning',
        //                 'The nature of the product has changed. Please create new product variants.'
        //             );
        //         }
        //     });
        //     return redirect(route('admin.market.product.index'))->with(
        //         'alert-section-success',
        //         'Product editing completed successfully.'
        //     );
        // }

        return DB::transaction(function () use ($request, $product, $inputs) {

            // cast
            $inputs['has_color'] = (int) ($inputs['has_color'] ?? 0);
            $inputs['has_size']  = (int) ($inputs['has_size']  ?? 0);

            // published_at
            if (empty($inputs['published_at'])) {
                $inputs['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
            } else {
                $inputs['published_at'] = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at'])->format('Y-m-d H:i:s');
            }

            // // image
            // if (isset($request['image'])) {
            //     $inputs['image'] = $request['image'];
            // }

            // update
            $product->update($inputs);

            // ---------------------------
            // تگ‌ها
            // ---------------------------
            if (!empty($inputs['tags'])) {
                $product->tags()->sync($inputs['tags']);
            } else {
                $product->tags()->detach();
            }

            // ---------------------------
            // لاجیک واریانت‌ها (عین نسخه خودت)
            // ---------------------------
            $variantCount = $product->variants()->count();

            // اگر هیچ رنگ/سایزی ندارد و هنوز واریانت ندارد → ساخت واریانت سیستمی
            if ($product->has_color == 0 && $product->has_size == 0 && $variantCount == 0) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id'   => null,
                    'size_id'    => null,
                    'price'      => $product->base_price,
                ]);
            }

            // اگر رنگ/سایز دار شد اما هنوز واریانت ندارد → draft
            if (($product->has_color || $product->has_size) && $variantCount == 0) {
                $product->update(['status' => 'draft']);
                return redirect()->route('admin.market.variant.create', $product)->with(
                    'alert-section-warning',
                    'To add a color or size field, please create or complete product variants.'
                );
            }

            // اگر ماهیت تغییر کرده
            if ($product->wasChanged(['has_color', 'has_size'])) {

                // حذف واریانت سیستمی
                $product->variants()
                    ->whereNull('color_id')
                    ->whereNull('size_id')
                    ->delete();

                // اگر بعد از تغییر، بدون رنگ/سایز شد → ساخت دوباره واریانت سیستمی
                if ($product->has_color == 0 && $product->has_size == 0) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id'   => null,
                        'size_id'    => null,
                        'price'      => $product->base_price,
                    ]);
                } else {
                    // اگر رنگ/سایز‌دار شد
                    return redirect()->route('admin.market.variant.create', $product)->with(
                        'alert-section-warning',
                        'The nature of the product has changed. Please create new product variants.'
                    );
                }
            }

            // ---------------------------
            // اگر هیچ redirect دیگری اتفاق نیفتاد
            // ---------------------------
            return redirect()->route('admin.market.product.index')->with(
                'alert-section-success',
                'Product editing completed successfully.'
            );
        });
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
