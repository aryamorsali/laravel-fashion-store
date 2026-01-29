<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Content\Banner;
use App\Models\Content\Post;
use App\Models\Market\HomeBox;
use App\Models\Market\Product;
use App\Models\Market\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home()
    {
        // Auth::loginUsingId(1);

        $banners = Banner::where('status', 1)->get();

        $boxes = HomeBox::where('status', 1)->get()->keyBy('position');

        $amazingProducts = Product::where('status', 'published')->whereHas('amazingSale', function ($q) {
            $q->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        })->with('amazingSale')->get()->sortByDesc(fn($p) => $p->amazingSale->percentage)->take(8);

        $topProducts = Product::bestSellers(30)->whereNotIn('id', $amazingProducts->pluck('id'))->take(8)->with('amazingSale')->get();

        $latestProducts = Product::where('status', 'published')->where('published_at', '<=', now())
        ->whereNotIn('id', $amazingProducts->pluck('id')->merge($topProducts->pluck('id')))->with('amazingSale')->orderBy('published_at', 'desc')->take(8)->get();
            
        $blogs = Post::where('status', 1)->where('published_at', '<=', now())->orderBy('published_at', 'desc')->take(3)->get();
    
        return view('customer.home', compact('banners', 'boxes', 'amazingProducts', 'blogs', 'topProducts', 'latestProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
