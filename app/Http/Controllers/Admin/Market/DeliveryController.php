<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\DeliveryRequest;
use App\Models\Market\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveries = Delivery::orderBy('created_at', 'desc')->get();
        return view('admin.market.delivery.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.market.delivery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DeliveryRequest $request)
    {
        $inputs = $request->validated();

        Delivery::create($inputs);
        return redirect()->route('admin.market.delivery.index')->with(
            'alert-section-success',
            'Your new delivery was successfully registered.'
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
    public function edit(Delivery $delivery)
    {
        return view('admin.market.delivery.edit', compact('delivery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DeliveryRequest $request, Delivery $delivery)
    {
        $inputs = $request->validated();
        $delivery->update($inputs);
        return redirect(route('admin.market.delivery.index'))->with(
            'alert-section-success',
            'delivery editing completed successfully.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect(route('admin.market.delivery.index'))->with(
            'alert-section-success',
            'delivery successfully deleted.'
        );
    }

    public function status(Delivery $delivery)
    {
        $delivery->status = $delivery->status == 0 ? 1 : 0;
        $result = $delivery->save();
        if ($result) {
            if ($delivery->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }
}
