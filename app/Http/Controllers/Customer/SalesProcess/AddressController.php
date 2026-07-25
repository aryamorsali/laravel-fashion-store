<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile\StoreAddressRequest;
use App\Http\Requests\Customer\Profile\UpdateAddressRequest;
use App\Models\Market\Address;
use App\Models\Market\CartItem;
use App\Models\Market\CommonDiscount;
use App\Models\Market\Coupon;
use App\Models\Market\Delivery;
use App\Models\Market\Order;
use App\Models\Market\Province;
use App\Services\CartCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function addressAndDelivery(CartCalculator $cartCalculator)
    {
        $cartItems = CartItem::where('user_id', Auth::user()->id)
            ->with('productVariant.amazingSale')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back();
        }

        $commonDiscount = CommonDiscount::where('status', 1)->where('start_date', '<=', now())->where('end_date', '>=', now())->first();

        $provinces = Province::with('cities')->get();

        $addresses = Auth::user()->addresses;
        $deliveries = Delivery::where('status', 1)->get();

        $totals = $cartCalculator->calculateCartTotals($cartItems, $commonDiscount, session('applied_coupon'));


        return view('customer.sales-process.address-and-delivery', compact(
            'cartItems',
            'commonDiscount',
            'totals',
            'provinces',
            'addresses',
            'deliveries'
        ));
    }

    public function getCities(Province $province)
    {
        return response()->json($province->cities()->select('id', 'name')->get());
    }


    public function storeAddress(StoreAddressRequest $request)
    {
        $inputs = $request->validated();

        Address::create([
            'user_id' => Auth::user()->id,
            'recipient_name' => $inputs['recipient_name'],
            'city_id' => $inputs['city_id'],
            'province_id' => $inputs['province_id'],
            'address' => $inputs['address'],
            'postal_code' => $inputs['postal_code'],
            'no' => $inputs['no'],
            'unit' => $inputs['unit'],
            'mobile' => $inputs['mobile'],
        ]);

        return redirect()->back()->with(
            'toast-success',
            'Address created successfuly.'
        );
    }

    public function updateAddress(UpdateAddressRequest $request, Address $address)
    {
        $inputs = $request->validated();

        $address->update($inputs);

        return redirect()->back()->with(
            'toast-success',
            'Address updated successfuly.'
        );
    }
}
