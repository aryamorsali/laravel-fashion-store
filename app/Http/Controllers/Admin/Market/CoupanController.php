<?php

namespace App\Http\Controllers\Admin\Market;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Market\CouponRequest;
use App\Models\Market\Coupon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CoupanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::all();
        return view('admin.market.discount.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('admin.market.discount.coupon.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request)
    {
        $inputs = $request->validated();

        if ($inputs['amount_type'] == 1) {
            $inputs['discount_ceiling'] = null;
        }

        $coupon = Coupon::create($inputs);
        if (Carbon::parse($inputs['end_date'])->isPast()) {
            $inputs['status'] = 2; // expired
        }
        return redirect()->route('admin.market.discount.coupon')->with(
            'alert-section-success',
            'Your new coupon was successfully registered.'
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
    public function edit(Coupon $coupon)
    {
        $users = User::all();
        return view('admin.market.discount.coupon.edit', compact('users', 'coupon'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $inputs = $request->validated();

        if ($inputs['amount_type'] == 1) {
            $inputs['discount_ceiling'] = null;
        }

        if (Carbon::parse($inputs['end_date'])->isPast()) {
            $inputs['status'] = 2; // expired
        }
        $coupon->update($inputs);
        return redirect(route('admin.market.discount.coupon'))->with(
            'alert-section-success',
            'Coupon successfully updated.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.market.discount.coupon')->with(
            'alert-section-success',
            'Coupon successfully deleted.'
        );
    }
}
