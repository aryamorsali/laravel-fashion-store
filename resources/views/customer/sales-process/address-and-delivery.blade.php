@extends('customer.layouts.app')

@section('head-tag')
    <title>Address And Delivery</title>

    <style>
        .total-price {
            font-size: 16px;
            font-weight: 600;
            color: #222;
        }

        /* ROW */
        .table_row {
            position: relative;
        }

        /* TOTAL-PRICE */
        .total-price {
            font-size: 16px;
            font-weight: 600;
            color: #222;
        }


        .w-full {
            width: 100% !important;
        }

        .size-120 {
            height: 75px !important;
        }

        .modal-backdrop {
            z-index: 1105 !important;
        }

        #addAddressModal,
        #editAddressModal {
            z-index: 1110 !important;
        }

        .modal-backdrop.show {
            z-index: 1105 !important;
        }

        .active-address-card {
            border: 2px solid rgb(113, 127, 224);
        }

        .active-delivery-card {
            border: 2px solid rgb(113, 127, 224);
        }

        .bor-top {
            border-top: 1px dashed #d9d9d9;
        }
    </style>
@endsection


@section('content')
    @include('admin.alerts.toast.success')
    @include('admin.alerts.toast.error')

    <!-- Breadcrumb -->
    <div class="container m-t-20">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('customer.home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            <a href="{{ route('customer.sales-process.shoping-cart') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Shopping Cart
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            <span class="stext-109 cl4">
                Shipping & Checkout
            </span>
        </div>
    </div>

    <!-- Checkout Form -->
    <div class="bg0 p-t-40 p-b-85">
        <div class="container">
            <div class="row">

                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">

                        <!--  Address Selection -->
                        <div class="p-b-30">
                            <h4 class="mtext-105 cl2 p-b-20 font-weight-bold" style="font-size: 1.5rem;">
                                1. Select Shipping Address
                            </h4>

                            <!-- Address Grid -->
                            <div class="row">

                                @foreach ($addresses as $index => $address)
                                    <div class="col-md-6 m-b-20 position-relative">

                                        <input type="radio" name="address_id" form="myForm" value="{{ $address->id }}"
                                            id="a-{{ $address->id }}" @checked(old('address_id', $index === 0 ? $address->id : null) == $address->id)
                                            class="d-none address-radio">


                                        <label for="a-{{ $address->id }}" class="w-100 m-b-0" style="cursor: pointer;">
                                            <div class="address-card bor13 p-all-20 pointer trans-04 position-relative {{ $index === 0 ? 'active-address-card' : '' }}"
                                                style="border: {{ old('address_id', $index === 0 ? $address->id : null) == $address->id ? '2px solid rgb(113, 127, 224)' : '1px solid #e6e6e6' }};
                                                 border-radius: 4px; background: #fff; min-height: 180px;">

                                                <div class="flex-w flex-sb-m p-b-12"
                                                    style="border-bottom: 1px dashed #e6e6e6; margin-bottom: 12px;">
                                                    <span class="stext-101 cl2 font-weight-bold text-uppercase">
                                                        <i class="fa fa-user m-r-6 text-secondary"></i>
                                                        {{ $address->recipient_name }}
                                                    </span>

                                                    <span
                                                        class="badge-selected stext-115 bg-dark cl1 text-white p-lr-8 p-tb-2"
                                                        style="border-radius:3px; font-size:10px; letter-spacing:1px; display: {{ old('address_id', $index === 0 ? $address->id : null) == $address->id ? '' : 'none' }};">
                                                        SELECTED
                                                    </span>
                                                </div>

                                                <div class="address-details">
                                                    <p class="stext-102 cl6 m-b-6" style="line-height: 1.5;">
                                                        <i class="fa fa-map-marker m-r-8 text-secondary"
                                                            style="width: 12px;"></i>
                                                        <span class="font-weight-bold cl3">
                                                            {{ $address->province->name ?? 'Province' }},
                                                            {{ $address->city->name ?? 'City' }}
                                                        </span><br>
                                                        <span style="padding-left: 20px;">
                                                            {{ $address->address }}
                                                            @if ($address->unit)
                                                                - Unit {{ $address->unit }}
                                                            @endif
                                                        </span>
                                                    </p>

                                                    <p class="stext-102 cl6 m-b-6">
                                                        <i class="fa fa-envelope m-r-8 text-secondary"
                                                            style="width: 12px;"></i>
                                                        <span class="cl9">Postal Code:</span>
                                                        {{ $address->postal_code ?? 'N/A' }}
                                                    </p>

                                                    <div class="flex-w flex-sb-m m-t-10">
                                                        <p class="stext-102 cl6 m-b-0">
                                                            <i class="fa fa-phone m-r-8 text-secondary"
                                                                style="width: 12px;"></i>
                                                            <span class="cl9">Phone:</span> {{ $address->mobile }}
                                                        </p>

                                                    </div>
                                                </div>

                                            </div>
                                        </label>



                                        {{-- edit modal دکمه --}}
                                        <button type="button" class="btn p-0 border-0 bg-transparent cl9 hov-cl1 trans-04"
                                            data-toggle="modal" data-target="#editAddressModal{{ $address->id }}"
                                            title="Edit Address"
                                            style="position: absolute; bottom: 20px; right: 30px; z-index: 10;">
                                            <i class="fa fa-edit text-secondary" style="font-size:18px;"></i>
                                        </button>

                                    </div>
                                @endforeach


                                <!-- Add New Address Card -->
                                <div class="col-md-6 m-b-20">
                                    <div class="bor13 p-all-20 pointer trans-04 flex-c-m flex-col"
                                        style="border: 2px dashed #ccc; border-radius: 4px; height: 100%; min-height: 160px; background: #fafafa;"
                                        data-toggle="modal" data-target="#addAddressModal">
                                        <i class="fa fa-plus-circle cl8 m-b-10" style="font-size: 2rem;"></i>
                                        <span class="stext-101 cl8">Add New Address</span>
                                    </div>
                                </div>
                            </div>


                            {{-- مودال ویرایش آدرس --}}

                            @foreach ($addresses as $address)
                                @php
                                    // بررسی می‌کنیم که آیا این فرم همان فرمی است که خطا داده
                                    $isEditFormValidating =
                                        old('modal_form_type') === 'edit' && old('address_id') == $address->id;
                                @endphp

                                <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1"
                                    role="dialog" style="z-index: 1110 !important;" aria-hidden="true">

                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">

                                            <!-- هدر مودال -->
                                            <div class="modal-header p-t-20 p-b-20 p-lr-25"
                                                style="border-bottom: 1px solid #f1f1f1; background-color: #fafafa;">
                                                <h5 class="modal-title stext-101 cl2 font-weight-bold"
                                                    id="addAddressModalLabel"
                                                    style="text-transform: uppercase; letter-spacing: 1px;">
                                                    Edit Address
                                                </h5>
                                                <button type="button" class=" close btn-close" data-dismiss="modal"
                                                    aria-label="Close"
                                                    style="font-size: 24px; color: #888; border: none; background: transparent; cursor: pointer;">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <!-- فرم ثبت آدرس -->
                                            <form action="{{ route('customer.sales-process.update-address', $address) }}"
                                                method="POST" id="updateAddressForm{{ $address->id }}">
                                                @csrf
                                                @method('PUT')
                                                {{-- برای مدیریت ارورها --}}
                                                <input type="hidden" name="address_id" value="{{ $address->id }}">

                                                {{-- برای اینکه مشخص کنیم آیا عملیات در حال انجام از نوع ویرایش است یا ثبت جدید --}}
                                                <input type="hidden" name="modal_form_type" value="edit">

                                                <div class="modal-body p-t-25 p-b-20 p-lr-25"
                                                    style="max-height: 70vh; overflow-y: auto;">


                                                    <div class="row">
                                                        <!-- انتخاب استان -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">Province <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="bor8 pos-relative"
                                                                style="background-color: #fff;">

                                                                <select class="stext-111 cl2 size-111 p-lr-15 w-full"
                                                                    name="province_id"
                                                                    id="editProvinceSelect{{ $address->id }}"
                                                                    data-old="{{ $address->province_id }}"
                                                                    style="border: none; outline: none; -webkit-appearance: none; -moz-appearance: none; appearance: none;
                                                                    background: transparent; cursor: pointer;">

                                                                    <option value="">Select Province</option>

                                                                    @foreach ($provinces as $province)
                                                                        <option value="{{ $province->id }}"
                                                                            @selected($isEditFormValidating ? old('province_id', $address->province_id) == $province->id : $address->province_id == $province->id)>
                                                                            {{ $province->name }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>

                                                                <div
                                                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #555;">
                                                                    <i class="fa fa-angle-down"></i>
                                                                </div>

                                                            </div>

                                                            @error('province_id', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>

                                                        <!-- انتخاب شهر -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">City <span
                                                                    class="text-danger">*</span>
                                                            </label>

                                                            <div class="bor8 pos-relative"
                                                                style="background-color: #fff;">

                                                                <select class="stext-111 cl2 size-111 p-lr-15 w-full"
                                                                    name="city_id" id="editCitySelect{{ $address->id }}"
                                                                    data-old="{{ $address->city_id }}"
                                                                    data-selected="{{ $isEditFormValidating ? old('city_id') : $address->city_id }}"
                                                                    disabled
                                                                    style="border: none; outline: none; -webkit-appearance: none; -moz-appearance: none; appearance: none;
                                                                        background: transparent; cursor: pointer;">

                                                                    <option value="">Select City</option>
                                                                </select>
                                                                <div
                                                                    style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #555;">
                                                                    <i class="fa fa-angle-down"></i>
                                                                </div>
                                                            </div>

                                                            @error('city_id', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <!-- آدرس کامل -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">Full Street Address
                                                                <span class="text-danger">*</span></label>
                                                            <div class="bor8 pos-relative">
                                                                <textarea class="stext-111 cl2 plh3 p-lr-15 p-tb-10 w-full" name="address" rows="2"
                                                                    placeholder="Street, Alley, Block, etc." style="border: none; resize: none; outline: none;">{{ $isEditFormValidating ? old('address') : $address->address }}</textarea>
                                                            </div>
                                                            @error('address', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>

                                                        <!-- نام گیرنده -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">Recipient Name <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="bor8 pos-relative">
                                                                <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full"
                                                                    type="text" name="recipient_name"
                                                                    placeholder="e.g. Arya"
                                                                    value="{{ $isEditFormValidating ? old('recipient_name') : $address->recipient_name }}">
                                                            </div>
                                                            @error('recipient_name', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <!-- شماره تماس -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">Phone Number <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="bor8 pos-relative">
                                                                <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full"
                                                                    type="tel" name="mobile"
                                                                    placeholder="e.g. 09123456789"
                                                                    value="{{ $isEditFormValidating ? old('mobile') : $address->mobile }}">
                                                            </div>

                                                            @error('mobile', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>

                                                        <!-- کد پستی -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">Postal Code <span
                                                                    class="text-danger">*</span></label>
                                                            <div class="bor8 pos-relative">
                                                                <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full"
                                                                    type="text" name="postal_code"
                                                                    placeholder="e.g. 1234567890"
                                                                    value="{{ $isEditFormValidating ? old('postal_code') : $address->postal_code }}">
                                                            </div>

                                                            @error('postal_code', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="row">
                                                        <!-- واحد / پلاک -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">Unit</label>
                                                            <div class="bor8 pos-relative">
                                                                <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full"
                                                                    type="text" name="unit"
                                                                    placeholder="e.g. Unit 12"
                                                                    value='{{ $isEditFormValidating ? old('unit') : $address->unit }}'>
                                                            </div>
                                                            @error('unit', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>

                                                        <!-- واحد / پلاک -->
                                                        <div class="col-sm-6 m-b-20">
                                                            <label class="stext-110 cl2 m-b-5">No</label>
                                                            <div class="bor8 pos-relative">
                                                                <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full"
                                                                    type="text" name="no" placeholder="e.g. No 4"
                                                                    value='{{ $isEditFormValidating ? old('no') : $address->no }}'>
                                                            </div>
                                                            @error('no', 'updateAddress')
                                                                @if ($isEditFormValidating)
                                                                    <div class="text-danger"
                                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                @endif
                                                            @enderror
                                                        </div>
                                                    </div>

                                                </div>

                                                <!-- فوتر مودال دکمه‌ها -->
                                                <div class="modal-footer p-t-15 p-b-20 p-lr-25"
                                                    style="border-top: 1px solid #f1f1f1; background-color: #fafafa;">
                                                    <button type="button" data-dismiss="modal"
                                                        class="flex-c-m stext-101 cl2 size-115 bg2 bor1 hov-btn1 p-lr-15 trans-04 m-r-10"
                                                        style="min-width: 100px;">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                        class="flex-c-m stext-101 cl0 size-115 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                                                        style="min-width: 140px;">
                                                        Update Address
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <hr class="m-tb-30" style="border-top: 1px solid #e6e6e6;">

                        <!-- Shipping Methods -->
                        <div>
                            <h4 class="mtext-105 cl2 p-b-20 font-weight-bold" style="font-size: 1.5rem;">
                                2. Choose Delivery Method
                            </h4>

                            <div class="flex-col">

                                @foreach ($deliveries as $index => $delivery)
                                    <input type="radio" name="delivery_id" value="{{ $delivery->id }}"
                                        data-price="{{ $delivery->delivery_cost }}" id="d-{{ $delivery->id }}"
                                        form="myForm" class="m-r-15 d-none delivery-radio" @checked(old('delivery_id', $index === 0 ? $delivery->id : null) == $delivery->id)>

                                    <label for="d-{{ $delivery->id }}"
                                        class="delivery-card flex-w flex-sb-m bor13 p-tb-15 p-lr-20 pointer trans-04 m-b-15"
                                        style="border-radius: 4px; background: #fff; border: {{ old('delivery_id', $index === 0 ? $delivery->id : null) == $delivery->id ? '2px solid rgb(113, 127, 224)' : '1px solid #e6e6e6' }};">

                                        <div class="flex-w flex-m">

                                            <div>
                                                <span
                                                    class="stext-101 cl2 font-weight-bold d-block">{{ $delivery->name }}</span>
                                                <span class="stext-115 cl6">Delivery within
                                                    {{ $delivery->delivery_days }}
                                                    business days.</span>
                                            </div>
                                        </div>
                                        <span
                                            class="stext-101 cl1 text-success font-weight-bold">${{ $delivery->delivery_cost }}</span>

                                    </label>
                                @endforeach

                            </div>
                        </div>

                    </div>
                </div>

                @if ($cartItems->count() >= 1)
                    <!-- RIGHT COLUMN: Order Summary -->
                    <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                        <div class="bor10 p-lr-37 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                            <h4 class="mtext-109 cl2 p-b-30">
                                Cart Totals
                            </h4>

                            <div class="flex-w flex-t p-b-13 bor12">
                                <div class="size-209">
                                    <span class="stext-110 cl2">
                                        Product prices({{ $cartItems->sum('quantity') }}):
                                    </span>
                                </div>

                                <div class="size-208">
                                    <span class="mtext-110 cl2" id="productPrices">
                                        ${{ rtrim(rtrim(number_format($totals['productPrices'], 2), '0'), '.') }}
                                    </span>
                                </div>
                            </div>


                            <div class="flex-w flex-t p-b-13 pt-3" id="productDiscountsRow">
                                <div class="size-209">
                                    <span class="stext-110 cl2">
                                        Product discounts:
                                    </span>
                                </div>

                                <div class="size-208">
                                    <span class="mtext-110 cl2 text-danger" id="productDiscounts">
                                        ${{ rtrim(rtrim(number_format($totals['productDiscounts'], 2), '0'), '.') }}
                                    </span>
                                </div>
                            </div>


                            <div class="flex-w flex-t p-b-13" id="commonDiscountRow"
                                @if (!($commonDiscount && $totals['commonDiscountAmount'] > 0)) hidden @endif>

                                <div class="size-209">
                                    <span class="stext-110 cl2">
                                        Common discount:
                                    </span>
                                </div>
                                <div class="size-208">
                                    <span class="mtext-110 cl2 text-danger" id="commonDiscountPercentage">
                                        {{ $commonDiscount?->percentage }}%
                                    </span>
                                    - <span class="mtext-110 cl2 text-warning" id="commonDiscountAmount">
                                        ${{ number_format($totals['commonDiscountAmount'], 2) }}
                                    </span>
                                </div>

                            </div>

                            <div class="flex-w flex-t p-b-13" id="couponDiscountBox"
                                @if ($totals['couponDiscount'] <= 0) hidden @endif>
                                <div class="size-209">
                                    <span class="stext-110 cl2">
                                        Coupon discount:
                                    </span>
                                </div>

                                <div class="size-208">
                                    <span class="mtext-110 cl2 text-danger" id="couponDiscountValue">
                                        ${{ number_format($totals['couponDiscount'], 2) }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex-w flex-t p-b-13" id="ShippingCostBox">
                                <div class="size-209">
                                    <span class="stext-110 cl2">
                                        Shipping cost:
                                    </span>
                                </div>

                                <div class="size-208">
                                    <span class="mtext-110 cl2 text-success" id="ShippingCostValue">
                                    </span>
                                </div>
                            </div>

                            <div class="flex-w flex-t p-t-27 p-b-33 bor-top">
                                <div class="size-208">
                                    <span class="mtext-101 cl2">
                                        Total:
                                    </span>
                                </div>

                                <div class="size-209 p-t-1">
                                    <span class="mtext-110 cl2" id="totalCartPrice">
                                        ${{ number_format($totals['totalCartPrice'], 2) }}
                                    </span>
                                </div>
                            </div>

                            <form id="myForm" method="POST"
                                action="{{ route('customer.sales-process.payment') }}">
                                @csrf
                            </form>
                            <button onclick="document.getElementById('myForm').submit();"
                                class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                                Proceed to Payment
                            </button>
                        </div>


                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Modal ثبت آدرس جدید -->

    <div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header p-t-20 p-b-20 p-lr-25"
                    style="border-bottom: 1px solid #f1f1f1; background-color: #fafafa;">
                    <h5 class="modal-title stext-101 cl2 font-weight-bold">Add New Address</h5>
                    <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"
                        style="font-size: 24px; color: #888; border: none; background: transparent; cursor: pointer;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div class="modal-body">

                    <!-- فرم ثبت آدرس -->
                    <form action="{{ route('customer.sales-process.store-address') }}" method="POST"
                        id="addAddressForm">
                        @csrf

                        {{-- برای اینکه مشخص کنیم آیا عملیات در حال انجام از نوع ویرایش است یا ثبت جدید --}}
                        <input type="hidden" name="modal_form_type" value="add">

                        @php
                            $isAddFormValidating = old('modal_form_type') === 'add';
                        @endphp

                        <div class="modal-body p-t-25 p-b-20 p-lr-25" style="max-height: 70vh; overflow-y: auto;">
                            <div class="row">
                                <!-- انتخاب استان -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">Province <span class="text-danger">*</span></label>
                                    <div class="bor8 pos-relative" style="background-color: #fff;">
                                        <select class="stext-111 cl2 size-111 p-lr-15 w-full" name="province_id"
                                            id="provinceSelect"
                                            style="border: none; outline: none; -webkit-appearance: none; -moz-appearance: none; appearance: none; background: transparent; cursor: pointer;">
                                            <option value="">Select Province</option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}" @selected($isAddFormValidating && old('province_id') == $province->id)>
                                                    {{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #555;">
                                            <i class="fa fa-angle-down"></i>
                                        </div>
                                    </div>
                                    @error('province_id', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <!-- انتخاب شهر -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">City <span class="text-danger">*</span></label>
                                    <div class="bor8 pos-relative" style="background-color: #fff;">
                                        <select class="stext-111 cl2 size-111 p-lr-15 w-full" name="city_id"
                                            id="citySelect"
                                            {{ $isAddFormValidating && old('province_id') ? '' : 'disabled' }}
                                            data-selected="{{ $isAddFormValidating ? old('city_id') : '' }}"
                                            style="border: none; outline: none; -webkit-appearance: none; -moz-appearance: none; appearance: none; background: transparent; cursor: pointer;">
                                            <option value="">Select City</option>
                                        </select>
                                        <div
                                            style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #555;">
                                            <i class="fa fa-angle-down"></i>
                                        </div>
                                    </div>
                                    @error('city_id', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            <div class="row">
                                <!-- آدرس کامل -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">Full Street Address <span
                                            class="text-danger">*</span></label>
                                    <div class="bor8 pos-relative">
                                        <textarea class="stext-111 cl2 plh3 p-lr-15 p-tb-10 w-full" name="address" rows="2"
                                            placeholder="Street, Alley, Block, etc." style="border: none; resize: none; outline: none;">{{ $isAddFormValidating ? old('address') : '' }}</textarea>
                                    </div>
                                    @error('address', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <!-- نام گیرنده -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">Recipient Name <span
                                            class="text-danger">*</span></label>
                                    <div class="bor8 pos-relative">
                                        <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full" type="text"
                                            name="recipient_name" placeholder="e.g. Arya"
                                            value="{{ $isAddFormValidating ? old('recipient_name') : '' }}">
                                    </div>
                                    @error('recipient_name', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- شماره تماس -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">Phone Number <span
                                            class="text-danger">*</span></label>
                                    <div class="bor8 pos-relative">
                                        <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full" type="tel"
                                            name="mobile" placeholder="e.g. 09123456789"
                                            value="{{ $isAddFormValidating ? old('mobile') : '' }}">
                                    </div>
                                    @error('mobile', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <!-- کد پستی -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">Postal Code <span
                                            class="text-danger">*</span></label>
                                    <div class="bor8 pos-relative">
                                        <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full" type="text"
                                            name="postal_code" placeholder="e.g. 1234567890"
                                            value="{{ $isAddFormValidating ? old('postal_code') : '' }}">
                                    </div>
                                    @error('postal_code', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="row">
                                <!-- واحد / پلاک -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">Unit</label>
                                    <div class="bor8 pos-relative">
                                        <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full" type="text"
                                            name="unit" placeholder="e.g. Unit 12"
                                            value='{{ $isAddFormValidating ? old('unit') : '' }}'>
                                    </div>
                                    @error('unit', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <!-- واحد / پلاک -->
                                <div class="col-sm-6 m-b-20">
                                    <label class="stext-110 cl2 m-b-5">No</label>
                                    <div class="bor8 pos-relative">
                                        <input class="stext-111 cl2 plh3 size-111 p-lr-15 w-full" type="text"
                                            name="no" placeholder="e.g. No 4"
                                            value='{{ $isAddFormValidating ? old('no') : '' }}'>
                                    </div>
                                    @error('no', 'storeAddress')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="modal-footer p-t-15 p-b-20 p-lr-25"
                            style="border-top: 1px solid #f1f1f1; background-color: #fafafa;">
                            <button type="button"
                                class="flex-c-m stext-101 cl2 size-115 bg2 bor1 hov-btn1 p-lr-15 trans-04 m-r-10"
                                data-dismiss="modal" style="min-width: 100px;">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-c-m stext-101 cl0 size-115 bg1 bor1 hov-btn1 p-lr-15 trans-04"
                                style="min-width: 140px;">
                                Save Address
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        //  منطق انتخاب شهر و استان

        // ========================
        // تابع مشترک بارگذاری شهرها
        // ========================

        function loadCities(provinceId, citySelect, selectedCityId = null) {
            citySelect.innerHTML = '<option value="">Loading...</option>';
            citySelect.disabled = true;

            fetch('/provinces/' + provinceId + '/cities')
                .then(r => r.json())
                .then(cities => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    if (cities.length > 0) {
                        citySelect.disabled = false;
                        cities.forEach(city => {
                            const opt = document.createElement('option');
                            opt.value = city.id;
                            opt.textContent = city.name;
                            if (selectedCityId && city.id == selectedCityId) {
                                opt.selected = true;
                            }
                            citySelect.appendChild(opt);
                        });
                    }
                })
                .catch(() => {
                    citySelect.innerHTML = '<option value="">Error loading cities</option>';
                    citySelect.disabled = true;
                });
        }

        // ========================
        // مودال ایجاد آدرس جدید 
        // ========================
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('provinceSelect');
            const citySelect = document.getElementById('citySelect');

            if (provinceSelect && citySelect) {
                provinceSelect.addEventListener('change', function() {
                    const provinceId = this.value;
                    if (!provinceId) {
                        citySelect.innerHTML = '<option value="">Select City</option>';
                        citySelect.disabled = true;
                        return;
                    }
                    loadCities(provinceId, citySelect);
                });

                // لود اولیه
                if (provinceSelect.value) {
                    const oldCity = citySelect.getAttribute('data-old');
                    loadCities(provinceSelect.value, citySelect, oldCity);
                }
            }
        });

        // ==========================================
        // مودال ادیت آدرس
        // ==========================================
        $(document).on('shown.bs.modal', '[id^="editAddressModal"]', function() {
            const modal = $(this);
            const provinceSelect = modal.find('[id^="editProvinceSelect"]')[0];
            const citySelect = modal.find('[id^="editCitySelect"]')[0];

            if (!provinceSelect || !citySelect) return;

            if (provinceSelect.value) {
                const selectedCity = citySelect.getAttribute('data-old');
                loadCities(provinceSelect.value, citySelect, selectedCity);
            }

            $(provinceSelect).off('change.edit').on('change.edit', function() {
                const provinceId = this.value;
                if (!provinceId) {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    citySelect.disabled = true;
                    return;
                }
                loadCities(provinceId, citySelect);
            });
        });



        //  Active card address switching 
        document.querySelectorAll('.address-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.address-card').forEach(function(card) {
                    card.classList.remove('active-address-card');
                    card.style.border = '1px solid #e6e6e6';
                    card.querySelector('.badge-selected').style.display = 'none';
                });

                var card = document.querySelector('label[for="' + this.id + '"] .address-card');
                card.classList.add('active-address-card');
                card.style.border = '2px solid rgb(113,127,224)';
                card.querySelector('.badge-selected').style.display = '';
            });
        });

        //  Active card delivery switching 
        const totalCartPrice = @json($totals['totalCartPrice']);

        document.querySelectorAll('.delivery-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.delivery-card').forEach(function(card) {
                    card.classList.remove('active-delivery-card');
                    card.style.border = '1px solid #e6e6e6';
                });

                var card = document.querySelector('label[for="' + this.id + '"]');
                if (card) {
                    card.classList.add('active-delivery-card');
                    card.style.border = '2px solid rgb(113,127,224)';
                }

                var shippingPrice = parseFloat(this.dataset.price) || 0;

                document.getElementById('ShippingCostValue').textContent = '$' + shippingPrice
                    .toLocaleString();

                var newTotal = totalCartPrice + shippingPrice;

                document.getElementById('totalCartPrice').textContent = '$' + newTotal.toLocaleString(
                    undefined, {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 2
                    });
            });
        });

        // اصلاح لود اولیه: اگر کاربر قبلاً روشی را انتخاب کرده (مثلاً بعد از ریدایرکت با خطا)
        var activeRadio = document.querySelector('.delivery-radio:checked');
        if (activeRadio) {
            // ایجاد یک Event مجازی برای اجرای کدهای تغییر قیمت در بارگذاری اولیه صفحه
            activeRadio.dispatchEvent(new Event('change'));
        } else {
            // مقداردهی اولیه هزینه ارسال به صفر در صورتی که چیزی انتخاب نشده باشد
            shippingCostEl.textContent = '$0';
        }
    </script>
@endsection
