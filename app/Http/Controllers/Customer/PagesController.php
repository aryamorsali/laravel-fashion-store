<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Content\About;
use App\Models\Content\Post;
use App\Models\Content\PostCategory;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class PagesController extends Controller
{
    public function about()
    {
        $about = About::first();
        return view('customer.pages.about', compact('about'));
    }

    public function blogs()
    {
        $blogs = Post::where('status', 1)->where('published_at', '<=', now())->orderBy('published_at', 'desc')->paginate(3);
        $categories = PostCategory::where('status', 1)->get();
        return view('customer.pages.blogs', compact('blogs', 'categories'));
    }

    public function contactForm()
    {
        return view('customer.pages.contact');
    }

    public function blogDetail(Post $blog)
    {
        $categories = PostCategory::where('status', 1)->get();
        return view('customer.pages.blog-detail', compact('blog', 'categories'));
    }
}
