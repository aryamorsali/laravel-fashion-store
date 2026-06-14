<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\WarehouseVariantRequest;
use App\Models\Market\ProductVariant;
use App\Models\Market\Warehouse;
use App\Models\Market\WarehouseTransaction;
use App\Models\Market\WarehouseVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Warehouse $warehouse)
    {
        // $warehouseVariants = $warehouse->variants()->with('productVariant')->paginate(15);
        $warehouseVariants = $warehouse->variants()->with('productVariant')->get();
        return view('admin.market.warehouse.warehouse-variant.index', compact('warehouseVariants', 'warehouse'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Warehouse $warehouse)
    {
        $variants = ProductVariant::with(['product', 'color', 'size'])->get();
        return view('admin.market.warehouse.warehouse-variant.create', compact('variants', 'warehouse'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseVariantRequest $request, Warehouse $warehouse)
    {
        DB::transaction(function () use ($request, $warehouse) {

            $data = $request->validated();

            $existing = WarehouseVariant::where('warehouse_id', $warehouse->id)
                ->where('product_variant_id', $request->product_variant_id)
                ->first();

            // اگر واریانت قبلی وجود داشت
            if ($existing) {
                $existing->stock = $request->stock;
                $existing->save();
                $existing->load('productVariant');
            } else {
                $data['warehouse_id'] = $warehouse->id;
                $data['reserved'] = 0;
                $data['sold'] = 0;
                $existing = WarehouseVariant::create($data);
                $existing->load('productVariant');
            }
            // ثبت تراکنش انبار
            $oldStock = $existing ? $existing->stock : 0;
            $quantityChange = $request->stock - $oldStock;
            if ($quantityChange != 0) {
                WarehouseTransaction::create([
                    'warehouse_id' => $warehouse->id,
                    'product_variant_id' => $request->product_variant_id,
                    'type' => $quantityChange > 0 ? 'in' : 'out', // افزایش → in، کاهش → out
                    'quantity' => abs($quantityChange),
                    'unit_price' => $existing
                        ? $existing->productVariant->price
                        : ProductVariant::find($request->product_variant_id)->price,
                ]);
            }
        });
        return redirect()->route('admin.market.warehouse.variant.index', $warehouse)->with(
            'alert-section-success',
            'Inventory corrected.'
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
    public function edit(Warehouse $warehouse, WarehouseVariant $warehouseVariant)
    {
        return view('admin.market.warehouse.warehouse-variant.edit', compact('warehouse', 'warehouseVariant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseVariantRequest $request, Warehouse $warehouse, WarehouseVariant $warehouseVariant)
    {
        DB::transaction(function () use ($request, $warehouse, $warehouseVariant) {
            $data = $request->validated();
            $data['warehouse_id'] = $warehouse->id;
            $oldStock = $warehouseVariant->stock;
            $newStock = $request->stock;
            $warehouseVariant->update($data);
            $warehouseVariant->load('productVariant');

            // ثبت تراکنش انبار با تغییرات
            if ($newStock != $oldStock) {
                $type = $newStock > $oldStock ? 'in' : 'out';
                $quantityChange = abs($newStock - $oldStock);

                WarehouseTransaction::create([
                    'warehouse_id' => $warehouse->id,
                    'product_variant_id' => $warehouseVariant->product_variant_id,
                    'type' => $type,
                    'quantity' => $quantityChange,
                    'unit_price' => $warehouseVariant->productVariant->price,
                ]);
            }
        });
        return redirect()->route('admin.market.warehouse.variant.index', $warehouse)->with(
            'alert-section-success',
            'Inventory corrected.'
        );
    }
}
