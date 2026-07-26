<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductCategoryRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Content\Tag;
use App\Models\Market\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productCategories = ProductCategory::orderBy('created_at', 'desc')->get();;
        return view('admin.market.category.index', compact('productCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();

        $parent_categories = ProductCategory::where('parent_id', null)->get();
        return view('admin.market.category.create', compact('parent_categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryRequest $request, ImageService $imageService)
    {
        $inputs = $request->validated();

        $tags = $inputs['tags'] ?? null;


        if ($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'product-category');
            $result = $imageService->createIndexAndSave($request->file('image'));

            if ($result === false) {
                return redirect()->route('admin.market.category.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        }

        if (empty($inputs['parent_id'])) {
            $inputs['parent_id'] = null;
        }
        DB::transaction(function () use ($inputs, $tags) {
            $productCategory = ProductCategory::create($inputs);

            // attach tags

            if ($tags) {
                $productCategory->tags()->sync($tags);
            }
        });
        return redirect()->route('admin.market.category.index')->with(
            'alert-section-success',
            'Your new category was successfully registered.'
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
    public function edit(ProductCategory $productCategory)
    {
        $tags = Tag::all();

        $parent_categories = ProductCategory::where('parent_id', null)->get()->except($productCategory->id);
        return view('admin.market.category.edit', compact('parent_categories', 'productCategory', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductCategoryRequest $request, ProductCategory $productCategory, ImageService $imageService)
    {
        $inputs = $request->validated();

        // اگر کاربر فایل جدید آپلود کرد
        if ($request->hasFile('image')) {
            if (!empty($productCategory->image)) {
                $imageService->deleteIndexFiles($productCategory->image['indexArray']);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'product-category');
            $result = $imageService->createIndexAndSave($request->file('image'));

            if ($result === false) {
                return redirect()->route('admin.market.category.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        } else {
            // اگر سایز انتخاب شده رو تغییر داد
            if (isset($inputs['currentImage']) && !empty($productCategory->image)) {
                $image = $productCategory->image;
                $image['currentImage'] = $inputs['currentImage'];
                $inputs['image'] = $image;
            }
        }
        if (empty($inputs['parent_id'])) {
            $inputs['parent_id'] = null;
        }

        DB::transaction(function () use ($inputs, $productCategory) {

            $productCategory->update($inputs);

            // ---------------------------
            // تگ‌ها
            if (!empty($inputs['tags'])) {
                $productCategory->tags()->sync($inputs['tags']);
            } else {
                $productCategory->tags()->detach();
            }
            // ---------------------------
        });

        return redirect(route('admin.market.category.index'))->with(
            'alert-section-success',
            'Category editing completed successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect(route('admin.market.category.index'))->with(
            'alert-section-success',
            'Category successfully deleted.'
        );
    }

    public function status(ProductCategory $productCategory)
    {
        $productCategory->status = $productCategory->status == 0 ? 1 : 0;
        $result = $productCategory->save();
        if ($result) {
            if ($productCategory->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
