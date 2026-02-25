<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\AmazingSaleRequest;
use App\Models\Market\AmazingSale;
use App\Models\Market\Product;
use App\Models\Market\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AmazingSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amazingSales = AmazingSale::with([
            'productVariant.product'
        ])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.market.discount.amazing_sale.index', compact('amazingSales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::with('variants')->get();
        return view('admin.market.discount.amazing_sale.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AmazingSaleRequest $request)
    {

        foreach ($request->product_variant_ids as $variantId) {
            AmazingSale::updateOrCreate(
                [
                    'product_variant_id' => $variantId,
                    'deleted_at' => null, 
                ],
                [
                    'percentage' => $request->percentage,
                    'is_active'  => $request->is_active,
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                ]
            );
        }

        return redirect()
            ->route('admin.market.discount.amazingSale')
            ->with('alert-section-success', 'Amazing sale created successfully.');
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
    public function edit(AmazingSale $amazingSale)
    {
        $products = Product::with('variants')->get();
        return view('admin.market.discount.amazing_sale.edit', compact('amazingSale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AmazingSaleRequest $request, AmazingSale $amazingSale)
    {
        
        $data = $request->validated();

        $amazingSale->update([
            'product_variant_id' => $amazingSale->product_variant_id,
            'percentage'         => $data['percentage'],
            'is_active'          => $data['is_active'],
            'start_date'         => $data['start_date'],
            'end_date'           => $data['end_date'],
        ]);

        return redirect()
            ->route('admin.market.discount.amazingSale')
            ->with(
                'alert-section-success',
                'Amazing sale successful updated.'
            );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AmazingSale $amazingSale)
    {
        $amazingSale->delete();
        return redirect()->route('admin.market.discount.amazingSale')->with(
            'alert-section-success',
            'Common discount successfully deleted.'
        );
    }
}
