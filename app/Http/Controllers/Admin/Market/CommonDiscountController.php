<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\CommonDiscountRequest;
use App\Models\Market\CommonDiscount;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommonDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_discounts = CommonDiscount::orderBy('created_at', 'desc')->get();
        return view('admin.market.discount.common_discount.index', compact('common_discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.market.discount.common_discount.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommonDiscountRequest $request)
    {
        $inputs = $request->validated();
        if (Carbon::parse($inputs['end_date'])->isPast()) {
            $inputs['status'] = 2; // expired
        }

        if ($inputs['status'] == 1) {
            // تمام تخفیف‌های فعال قبلی را غیرفعال کن
            CommonDiscount::where('status', 1)->update(['status' => 0]);
        }

        $common_discount = CommonDiscount::create($inputs);
        return redirect()->route('admin.market.discount.common_discount')->with(
            'alert-section-success',
            'Your new common discount was successfully registered.'
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
    public function edit(CommonDiscount $common_discount)
    {
        return view('admin.market.discount.common_discount.edit', compact('common_discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommonDiscountRequest $request, CommonDiscount $common_discount)
    {
        $inputs = $request->validated();
        if (Carbon::parse($inputs['end_date'])->isPast()) {
            $inputs['status'] = 2; // expired
        }

        if ($inputs['status'] == 1) {
            // بقیه را غیرفعال کن بجز همین که داریم آپدیت می‌کنیم
            CommonDiscount::where('id', '!=', $common_discount->id)
                ->where('status', 1)
                ->update(['status' => 0]);
        }

        $common_discount->update($inputs);
        return redirect(route('admin.market.discount.common_discount'))->with(
            'alert-section-success',
            'Common discount successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommonDiscount $common_discount)
    {
        $common_discount->delete();
        return redirect()->route('admin.market.discount.common_discount')->with(
            'alert-section-success',
            'Common discount successfully deleted.'
        );
    }
}
