<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Content\PostCategoryRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Content\PostCategory;
use App\Models\Content\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postCategoreis = PostCategory::all();
        return view('admin.content.category.index', compact('postCategoreis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();

        return view('admin.content.category.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCategoryRequest $request, ImageService $imageService)
    {
        $inputs = $request->validated();

        $tags = $inputs['tags'] ?? null;

        if ($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'post-category');
            $result = $imageService->createIndexAndSave($request->file('image'));

            if ($result === false) {
                return redirect()->route('admin.content.category.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        }

        DB::transaction(function () use ($inputs, $tags) {

            $postCategory = PostCategory::create($inputs);

            // attach tags
            if ($tags) {
                $postCategory->tags()->sync($tags);
            }
        });


        return redirect()->route('admin.content.category.index')->with(
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
    public function edit(PostCategory $postCategory)
    {
        $tags = Tag::all();

        return view('admin.content.category.edit', compact('postCategory', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostCategoryRequest $request, PostCategory $postCategory, ImageService $imageService)
    {
        $inputs = $request->validated();

        // اگر کاربر فایل جدید آپلود کرد
        if ($request->hasFile('image')) {
            if (!empty($postCategory->image)) {
                $imageService->deleteIndexFiles($postCategory->image['indexArray']);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'post-category');
            $result = $imageService->createIndexAndSave($request->file('image'));
            if ($result === false) {
                return redirect()->route('admin.content.category.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        } else {
            // اگر سایز انتخاب شده رو تغییر داد
            if (isset($inputs['currentImage']) && !empty($postCategory->image)) {
                $image = $postCategory->image;
                $image['currentImage'] = $inputs['currentImage'];
                $inputs['image'] = $image;
            }
        }

        DB::transaction(function () use ($inputs, $postCategory) {
            $postCategory->update($inputs);

            // ---------------------------
            // تگ‌ها
            // ---------------------------
            if (!empty($inputs['tags'])) {
                $postCategory->tags()->sync($inputs['tags']);
            } else {
                $postCategory->tags()->detach();
            }
        });

        return redirect(route('admin.content.category.index'))->with(
            'alert-section-success',
            'Category editing completed successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostCategory $postCategory)
    {
        $postCategory->delete();
        return redirect()->route('admin.content.category.index')->with(
            'alert-section-success',
            'Category successfully deleted.'
        );
    }

    public function status(PostCategory $postCategory)
    {
        $postCategory->status = $postCategory->status == 0 ? 1 : 0;
        $result = $postCategory->save();
        if ($result) {
            if ($postCategory->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
