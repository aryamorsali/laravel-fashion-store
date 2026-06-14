<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Models\Market\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = ProductSize::all();
        return view('admin.market.product.size.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.market.product.size.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:120|min:1|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
            'type' => 'nullable|max:150|regex:/^[ا-یa-zA-Z0-9\-۰-۹ء-ي., ]+$/u',
        ]);

        $sizes = explode(',', $request->name);
        $sizes = array_map('trim', $sizes);
        foreach ($sizes as $size) {
            ProductSize::create([
                'name' => $size,
                'type' => $request->type,
            ]);
        }

        return redirect()->route('admin.market.size.index')->with(
            'alert-section-success',
            'Your new size has been successfully registered.'
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
    public function destroy(ProductSize $size)
    {
        $size->delete();
        return redirect()->route('admin.market.size.index')->with(
            'alert-section-success',
            'Size successfully removed.'
        );
    }
}
