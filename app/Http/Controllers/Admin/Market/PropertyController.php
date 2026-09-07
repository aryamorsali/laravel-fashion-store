<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductAttributeRequest;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Models\Market\ProductAttribute;
use App\Models\Market\ProductCategory;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;

        $query = ProductAttribute::query();
        if ($request->filled('search')) {

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $productAttributes = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

        return view('admin.market.property.index', compact('productAttributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = ProductCategory::all();
        return view('admin.market.property.create', compact('productCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductAttributeRequest $request)
    {
        $data = $request->all();
        $data['is_global'] = $request->has('is_global');
        ProductAttribute::create($data);
        return redirect()->route('admin.market.property.index')
            ->with('alert-section-success', 'Attribute created successfully.');
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
    public function edit(ProductAttribute $productAttribute)
    {

        $productCategories = ProductCategory::all();
        return view('admin.market.property.edit', compact('productCategories', 'productAttribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductAttributeRequest $request, ProductAttribute $productAttribute)
    {
        $data = $request->validated();
        if ($request->has('is_global') && $request->is_global == 1) {
            $data['category_id'] = null;
            $data['is_global'] = 1;
        } else {
            $data['is_global'] = 0;
        }
        $productAttribute->update($data);

        return redirect()->route('admin.market.property.index')
            ->with('alert-section-success', 'Attribute updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();
        return redirect()->route('admin.market.property.index')->with(
            'alert-section-success',
            'Attribute successfully removed.'
        );
    }
}
