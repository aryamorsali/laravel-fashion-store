<?php

namespace App\Http\Controllers\Customer\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\About;
use App\Models\Content\Comment;
use App\Models\Content\ContactMessage;
use App\Models\Content\FAQ;
use App\Models\Content\Post;
use App\Models\Content\PostCategory;
use App\Models\Content\Tag;
use App\Models\Market\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function blogDetail(Post $post)
    {
        $categories = PostCategory::where('status', 1)->get();

        $tags = Tag::whereHas('posts', function ($q) {
            $q->where('status', 1)
                ->where('published_at', '<=', now());
        })->get();

        // کامنت های تایید شده
        $approvedComments = $post->activeComments()
            ->with(['user', 'children.user'])
            ->latest()
            ->paginate(3);

        // محصولات ویژه / برتر
        $featuredProducts = Product::bestSellers(30)
            ->whereHas('variants.warehouseVariants', function ($q) {
                $q->whereColumn('stock', '>', 'reserved');
            })
            ->with([
                'variants' => function ($q) {
                    $q->whereHas('warehouseVariants', function ($q) {
                        $q->whereColumn('stock', '>', 'reserved');
                    })
                        ->with([
                            'warehouseVariants',
                            'orderItems',
                            'amazingSale' => function ($q) {
                                $q->where('is_active', true)
                                    ->where('start_date', '<=', now())
                                    ->where('end_date', '>=', now());
                            },
                        ]);
                }
            ])
            ->take(3)
            ->get();

        return view('customer.content.blog-detail', compact('approvedComments', 'post', 'tags', 'categories', 'featuredProducts'));
    }

    public function addComment(Request $request, Post $post)
    {
        if ($post->commentable == 1) {
            return redirect()->back()->with(
                'toast-error',
                'This post does not have the ability to post comments.'
            );
        }

        $data  = $request->validate([
            'body' => 'required|max:2000',
        ]);

        Comment::create([
            'body' => $data['body'],
            'parent_id' => null,
            'author_id' => Auth::user()->id,
            'commentable_type' => Post::class,
            'commentable_id' => $post->id,
        ]);
        return redirect()->back()->with(
            'toast-success',
            'Thanks for your review! It’s pending admin approval.'
        );
    }



    public function blogs(?PostCategory $category, Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:100',
            'tag' => 'nullable|exists:tags,slug',
        ]);

        $categories = PostCategory::where('status', 1)->get();

        $tags = Tag::whereHas('posts', function ($q) {
            $q->where('status', 1)->where('published_at', '<=', now());
        })->get();

        $query = Post::where('status', 1)->where('published_at', '<=', now());

        // فیلتر تگ
        if ($request->tag) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // فیلتر دسته‌بندی
        if ($category && $category->exists) {
            $query->where('category_id', $category->id);
        }

        // جستجو بر اساس نام پست
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }
        $posts = $query->paginate(3);

        // محصولات ویژه / برتر
        $featuredProducts = Product::bestSellers(30)
            ->whereHas('variants.warehouseVariants', function ($q) {
                $q->whereColumn('stock', '>', 'reserved');
            })
            ->with([
                'variants' => function ($q) {
                    $q->whereHas('warehouseVariants', function ($q) {
                        $q->whereColumn('stock', '>', 'reserved');
                    })
                        ->with([
                            'warehouseVariants',
                            'orderItems',
                            'amazingSale' => function ($q) {
                                $q->where('is_active', true)
                                    ->where('start_date', '<=', now())
                                    ->where('end_date', '>=', now());
                            },
                        ]);
                }
            ])
            ->take(3)
            ->get();

        return view('customer.content.blogs', compact('posts', 'tags', 'categories', 'featuredProducts'));
    }


    public function about()
    {
        $about = About::first();
        return view('customer.content.about', compact('about'));
    }

    public function contact()
    {
        return view('customer.content.contact');
    }

    public function storeContact(Request $request)
    {
        $inputs = $request->validate([
            'email' => 'required|email',
            'body' => 'required|max:2000|min:5|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي.,><\/;\n\r& ]+$/u',
        ]);


        $contactMessage = ContactMessage::create([
            'email' => $inputs['email'],
            'body' => $inputs['body'],
            'user_id' => Auth::id() ?? null,
        ]);

        return redirect()->route('customer.content.contact')->with(
            'toast-success',
            'Your new message was successfully registered.'
        );
    }

    public function faq()
    {
        $faqs = FAQ::where('status', 1)->orderBy('created_at', 'desc')->paginate(8);
        return view('customer.content.faq', compact('faqs'));
    }
}
