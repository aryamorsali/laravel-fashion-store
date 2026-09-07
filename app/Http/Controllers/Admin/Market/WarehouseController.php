<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\SearchRequest;
use App\Http\Requests\Admin\Market\WarehouseRequest;
use App\Models\Market\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchRequest $request)
    {
        $validated = $request->validated();

        $search = $validated['search'] ?? null;

        $query = Warehouse::query();
        if ($request->filled('search')) {

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $warehouses = $query->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
        
        return view('admin.market.warehouse.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.market.warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request)
    {
        $data = $request->validated();
        $result = Warehouse::create($data);
        return redirect()->route('admin.market.warehouse.index')->with(
            'alert-section-success',
            'Warehouse successfully created.'
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
    public function edit(Warehouse $warehouse)
    {
        return view('admin.market.warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $inputs = $request->validated();
        $warehouse->update($inputs);
        return redirect(route('admin.market.warehouse.index'))->with(
            'alert-section-success',
            'Warehouse successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect(route('admin.market.warehouse.index'))->with(
            'alert-section-success',
            'Warehouse successfully deleted.'
        );
    }
}
