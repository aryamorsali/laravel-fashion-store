<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\ProductAttributeValueRequest;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Models\Market\Product;
use App\Models\Market\ProductAttribute;
use App\Models\Market\ProductAttributeValue;
use Illuminate\Http\Request;

class PropertyValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductAttribute $productAttribute, SearchRequest $request)
    {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;

        $values = $productAttribute->values()->with([
            'product',
            'productAttribute',
        ]);


        if ($request->filled('search')) {
            $values->where(function ($query) use ($search) {
                $query->where('value', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        $values = $values->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());

        return view('admin.market.property.value.index', compact('productAttribute', 'values'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ProductAttribute $productAttribute)
    {
        if ($productAttribute->category != null) {
            $products = $productAttribute->category->products;
        } elseif ($productAttribute->is_global == 1) {
            $products = Product::all();
        }
        return view('admin.market.property.value.create', compact('productAttribute', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductAttributeValueRequest $request, ProductAttribute $productAttribute)
    {
        $inputs = $request->all();
        $inputs['product_attribute_id'] = $productAttribute->id;
        ProductAttributeValue::create($inputs);
        return redirect()->route('admin.market.value.index', $productAttribute)->with(
            'alert-section-success',
            'The product attribute value created successfully.'
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
    public function edit(ProductAttribute $productAttribute, ProductAttributeValue $value)
    {
        if ($productAttribute->category != null) {
            $products = $productAttribute->category->products;
        } elseif ($productAttribute->is_global == 1) {
            $products = Product::all();
        }
        return view('admin.market.property.value.edit', compact('productAttribute', 'products', 'value'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductAttributeValueRequest $request, ProductAttribute $productAttribute, ProductAttributeValue $value)
    {
        $inputs = $request->validated();
        $inputs['product_attribute_id'] = $productAttribute->id;
        $value->update($inputs);
        return redirect()->route('admin.market.value.index', $productAttribute)->with(
            'alert-section-success',
            'The product attribute value updated successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAttribute $productAttribute, ProductAttributeValue $value)
    {
        $value->delete();
        return redirect()->route('admin.market.value.index', compact('productAttribute'))->with(
            'alert-section-success',
            'Attribute value successfully removed.'
        );
    }
}
