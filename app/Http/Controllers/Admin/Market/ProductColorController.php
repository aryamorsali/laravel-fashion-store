<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Models\Market\Product;
use App\Models\Market\ProductColor;
use Illuminate\Http\Request;

class ProductColorController extends Controller
{
    public function index(SearchRequest $request)
    {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;

        $query = ProductColor::query();
        if ($request->filled('search')) {

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $colors = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
        
        return view('admin.market.product.color.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.market.product.color.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|min:2|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'hex_code' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/'
            ],
        ]);
        $inputs = $request->all();
        $color = ProductColor::create($inputs);
        return redirect()->route('admin.market.color.index')->with(
            'alert-section-success',
            'Your new color has been successfully registered.'
        );
    }

    public function destroy(ProductColor $color)
    {
        $color->delete();
        return redirect()->route('admin.market.color.index')->with(
            'alert-section-success',
            'Color successfully removed.'
        );
    }
}
