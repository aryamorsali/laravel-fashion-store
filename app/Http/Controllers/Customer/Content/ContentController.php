<?php

namespace App\Http\Controllers\Customer\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Comment;
use App\Models\Content\Post;
use App\Models\Content\PostCategory;
use App\Models\Market\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentController extends Controller
{
    public function blogDetail(Post $post)
    {
        $categories = PostCategory::where('status', 1)->get();
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

        return view('customer.content.blog-detail', compact('approvedComments', 'post', 'categories', 'featuredProducts'));
    }

    public function addComment(Request $request, Post $post)
    {

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
}
