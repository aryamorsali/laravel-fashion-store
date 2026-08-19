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
        $data = $request->validated();

        $result = $this->addressService->storeAddress($data);

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
