<?php

namespace App\Http\Controllers\Customer\SalesProcess;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Profile\StoreAddressRequest;
use App\Http\Requests\Customer\Profile\UpdateAddressRequest;
use App\Models\Market\Address;
use App\Models\Market\Province;
use App\Services\AddressService;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    protected $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function addressAndDelivery()
    {

        $result = $this->addressService->addressAndDelivery();
        
        $cartItems = $result['cartItems'];
        $commonDiscount = $result['commonDiscount'];
        $totals = $result['totals'];
        $provinces = $result['provinces'];
        $addresses = $result['addresses'];
        $deliveries = $result['deliveries'];


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
