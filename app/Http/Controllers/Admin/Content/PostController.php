<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Content\PostRequest;
use App\Http\Services\Image\ImageService;
use App\Models\Content\Post;
use App\Models\Content\PostCategory;
use App\Models\Content\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.content.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::all();
        $postCategories = PostCategory::all();
        return view('admin.content.post.create', compact('postCategories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request, ImageService $imageService)
    {
        $inputs = $request->validated();

        $tags = $inputs['tags'] ?? null;

        if (empty($inputs['published_at'])) {
            $inputs['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $inputs['published_at'] = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at'])->format('Y-m-d H:i:s');
        }

        if ($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'post');
            $result = $imageService->createBlogImagesAndSave($request->file('image'));

            if ($result === false) {
                return redirect()->route('admin.content.post.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        }

        DB::transaction(function () use ($inputs, $tags) {
            $post = Post::create($inputs);

            // attach tags
            if ($tags) {
                $post->tags()->sync($tags);
            }
        });
        return redirect()->route('admin.content.post.index')->with(
            'alert-section-success',
            'Your new post was successfully registered.'
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
    public function edit(Post $post)
    {
        $tags = Tag::all();
        $postCategories = PostCategory::all();
        return view('admin.content.post.edit', compact('postCategories', 'post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, ImageService $imageService, Post $post)
    {
        $inputs = $request->validated();
        $tags = $inputs['tags'] ?? null;

        if (empty($inputs['published_at'])) {
            $inputs['published_at'] = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $inputs['published_at'] = Carbon::createFromFormat('Y-m-d H:i', $inputs['published_at'])->format('Y-m-d H:i:s');
        }

        if ($request->hasFile('image')) {
            if (!empty($post->image)) {
                $imageService->deleteIndexFiles($post->image['blogArray']);
            }
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'post');
            $result = $imageService->createBlogImagesAndSave($request->file('image'));
            if ($result === false) {
                return redirect()->route('admin.content.post.index')->with(
                    'alert-section-error',
                    'There was an error uploading the photo.'
                );
            }
            $inputs['image'] = $result;
        } else {
            if (isset($inputs['currentImage']) && !empty($post->image)) {
                $image = $post->image;
                $image['currentImage'] = $inputs['currentImage'];
                $inputs['image'] = $image;
            }
        }

        DB::transaction(function () use ($inputs, $post, $tags) {
            $post->update($inputs);

            // sync tags
            if ($tags) {
                $post->tags()->sync($tags);
            } else {
                // اگر تگ حذف شده باشد
                $post->tags()->detach();
            }
        });
        return redirect(route('admin.content.post.index'))->with(
            'alert-section-success',
            'Post editing completed successfully.'
        );
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.content.post.index')->with(
            'alert-section-success',
            'Post successfully deleted.'
        );
    }

    public function status(Post $post)
    {
        $post->status = $post->status == 0 ? 1 : 0;
        $result = $post->save();
        if ($result) {
            if ($post->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function commentable(Post $post)
    {
        $post->commentable = $post->commentable == 0 ? 1 : 0;
        $result = $post->save();
        if ($result) {
            if ($post->commentable == 0) {
                return response()->json(['commentable' => true, 'checked' => false]);
            } else {
                return response()->json(['commentable' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['commentable' => false]);
        }
    }
}
