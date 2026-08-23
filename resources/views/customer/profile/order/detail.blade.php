@extends('customer.layouts.app')

@section('head-tag')
    <title>Order Detail</title>

    <style>
        .profile-sidebar-menu {
            padding-top: 12px;
        }

        .profile-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 5px;
            border: 0;
            border-bottom: 1px solid #f0f0f0;
            background: transparent;
            color: #555;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
            transition: all .2s ease;
        }

        .profile-menu-item:hover,
        .profile-menu-item.active {
            color: #717fe0;
            padding-left: 10px;
        }

        .profile-menu-item.active {
            font-weight: 600;
        }

        .profile-logout:hover {
            color: #d9534f;
        }

        ///////////////////////////////////////////

        .back-to-orders-btn {
            min-width: 130px;
            height: 38px;
            padding: 0 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #333;
            background-color: #f7f7f7;
            border: 1px solid #e6e6e6;
            border-radius: 3px;
            font-family: Poppins-Medium, sans-serif;
            font-size: 12px;
            text-transform: uppercase;
        }

        .back-to-orders-btn:hover {
            color: #fff;
            background-color: #717fe0;
            border-color: #717fe0;
        }

        .order-info-card {
            background-color: #fafafa;
            border: 1px solid #e6e6e6;
            border-radius: 4px;
        }

        .fs-20 {
            font-size: 20px;
        }

        .fs-18 {
            font-size: 18px;
        }

        /* Badges */
        .order-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-family: Poppins-Regular, sans-serif;
            font-size: 12px;
            text-transform: capitalize;
        }

        .order-status-badge i {
            font-size: 7px;
        }

        .order-status-badge.order-status-0,
        .order-status-badge.order-status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .order-status-badge.order-status-1,
        .order-status-badge.order-status-processing {
            background-color: #e8eaf6;
            color: #3f51b5;
        }

        .order-status-badge.order-status-2,
        .order-status-badge.order-status-delivered,
        .order-status-badge.order-status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .order-status-badge.order-status-3,
        .order-status-badge.order-status-returned,
        .order-status-badge.order-status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .payment-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-family: Poppins-Medium, sans-serif;
            text-transform: uppercase;
        }

        .payment-badge.paid {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .payment-badge.unpaid {
            background-color: #fffde7;
            color: #f57f17;
        }

        .no-img-placeholder {
            width: 60px;
            height: 60px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 22px;
        }

        .order-note-box {
            border: 1px solid #eee;
            background-color: #fbfbfb;
        }

        .underline {
            text-decoration: underline !important;
        }

        /* PRICE */
        .price-box {
            font-size: 16px;
            font-weight: 600;
            color: #222;
        }

        .price-old {
            text-decoration: line-through;
            color: #999;
            font-size: 13px;
            margin-right: 6px;
        }

        .price-discount {
            color: #e60023;
            font-weight: bold;
            font-size: 12px;
        }


        @media (max-width: 767.98px) {
            .table-shopping-cart .column-1 {
                padding-left: 10px;
            }

            .table-shopping-cart .column-2 {
                min-width: 140px;
            }

            .back-to-orders-btn {
                margin-top: 15px;
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    @include('admin.alerts.toast.success')
    @include('admin.alerts.toast.error')

    <section class="bg0 p-t-85 p-b-85">
        <div class="container">

            {{-- Breadcrumb --}}
            <div class="flex-w flex-r-m p-b-35">
                <a href="{{ route('customer.home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Home
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <a href="{{ route('customer.profile.profile') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    My Account
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <a href="{{ route('customer.profile.my-orders') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    My Orders
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <span class="stext-109 cl4">
                    #{{ $order->id }}
                </span>
            </div>

            <div class="row">
                {{-- Profile Sidebar --}}
                @include('customer.layouts.partials.profile-sidebar')

                {{-- Main Content --}}
                <div class="col-lg-9 col-md-8">
                    <div class="bor10 p-lr-40 p-t-35 p-b-40 m-b-30">

                        {{-- Header & Actions --}}
                        <div class="flex-w flex-sb-m p-b-25 bor12">
                            <div>
                                <div class="flex-w flex-m">
                                    <h3 class="mtext-111 cl2 p-b-4 m-r-15">
                                        Order #{{ $order->id }}
                                    </h3>
                                    <span class="order-status-badge order-status-{{ $order->order_status }} m-b-4">
                                        <i class="fa fa-circle m-r-5"></i>
                                        {{ str_replace('_', ' ', ucfirst($order->order_status)) }}
                                    </span>
                                </div>

                                <p class="stext-109 cl6">
                                    Placed on <span
                                        class="cl2">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </p>
                            </div>

                            <a href="{{ route('customer.profile.my-orders') }}"
                                class="back-to-orders-btn hov-btn1 trans-04">
                                <i class="fa fa-arrow-left m-r-6"></i> Back to Orders
                            </a>
                        </div>

                        {{-- Order Metadata Cards --}}
                        <div class="row p-t-30 p-b-15">
                            {{-- Shipping Address --}}
                            <div class="col-md-6 col-lg-6 m-b-20">
                                <div class="order-info-card bor10 p-all-20 h-100">
                                    <div class="flex-w flex-m p-b-12">
                                        <i class="zmdi zmdi-pin cl1 fs-20 m-r-10"></i>
                                        <h5 class="stext-110 cl2 font-weight-bold">Shipping Address</h5>
                                    </div>
                                    <p class="stext-107 cl6 m-b-5">
                                        <strong class="cl2">Recipient:</strong>
                                        {{ $order->address->recipient_name ?? auth()->user()->name }}
                                    </p>
                                    <p class="stext-107 cl6 m-b-5">
                                        <strong class="cl2">Phone:</strong>
                                        {{ $order->address->mobile ?? (auth()->user()->mobile ?? '-') }}
                                    </p>
                                    <p class="stext-107 cl6 m-b-5">
                                        <strong class="cl2">Postal Code:</strong>
                                        {{ $order->address->postal_code ?? '-' }}
                                    </p>
                                    <p class="stext-107 cl6">
                                        <strong class="cl2">Address:</strong>
                                        {{ $order->address->province->name ?? '' }} -
                                        {{ $order->address->city->name ?? '' }},
                                        {{ $order->address->address ?? 'No address recorded' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Payment & Delivery Method --}}
                            <div class="col-md-6 col-lg-6 m-b-20">
                                <div class="order-info-card bor10 p-all-20 h-100">
                                    <div class="flex-w flex-m p-b-12">
                                        <i class="zmdi zmdi-card cl1 fs-20 m-r-10"></i>
                                        <h5 class="stext-110 cl2 font-weight-bold">Payment & Delivery</h5>
                                    </div>
                                    <p class="stext-107 cl6 m-b-6 flex-w flex-sb-m">
                                        <span class="cl2">Payment Status:</span>
                                        @if ($order->payment_status === 'paid')
                                            <span class="payment-badge paid"><i class="fa fa-check-circle m-r-4"></i>
                                                Paid</span>
                                        @else
                                            <span class="payment-badge unpaid"><i class="fa fa-clock-o m-r-4"></i>
                                                {{ ucfirst($order->payment_status) }}</span>
                                        @endif
                                    </p>
                                    <p class="stext-107 cl6 m-b-6 flex-w flex-sb-m">
                                        <span class="cl2">Payment Method:</span>
                                        <span class="cl3">{{ ucfirst($order->payment_type ?? 'Online Gateway') }}</span>
                                    </p>
                                    <p class="stext-107 cl6 m-b-6 flex-w flex-sb-m">
                                        <span class="cl2">Delivery Method:</span>
                                        <span class="cl3">{{ $order->delivery->name }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Order Items Table --}}
                        <div class="p-t-20 p-b-20">
                            <h4 class="mtext-109 cl2 p-b-20">
                                Order Items ({{ $order->orderItems->sum('quantity') }})
                            </h4>

                            <div class="wrap-table-shopping-cart bor10">
                                <table class="table-shopping-cart">
                                    <tr class="table_head">
                                        <th class="column-1">Product</th>
                                        <th class="column-2"></th>
                                        <th class="column-3">Price</th>
                                        <th class="column-4 text-center">Quantity</th>
                                        <th class="column-5 text-right p-r-30">Total</th>
                                    </tr>

                                    @foreach ($order->orderItems as $item)
                                        @php

                                            $finalProductPrice = $item->final_product_price;
                                            $totalItemPrice = $item->final_total_price;

                                            $product = $item->productVariant?->product;
                                        @endphp
                                        <tr class="table_row">
                                            <td class="column-1">
                                                <div class="how-itemcart1">
                                                    <img src="{{ asset($product->image['indexArray']['main']) }}"
                                                        alt="{{ $product->name }}">
                                                </div>
                                            </td>
                                            <td class="column-2">
                                                @if ($product)
                                                    <a href="{{ route('customer.market.product', [$product->slug, 'variant' => $item->product_variant_id]) }}"
                                                        class="stext-104 cl2 hov-cl1 trans-04 font-weight-bold">
                                                        {{ $product->name }}
                                                    </a>
                                                @else
                                                    <span
                                                        class="stext-104 cl2 font-weight-bold">{{ $product->name }}</span>
                                                @endif

                                                {{-- Variant Attributes --}}
                                                @if ($item->productVariant)
                                                    <div class="stext-111 cl6 p-t-4">
                                                        Color :
                                                        @if ($item->productVariant->color)
                                                            {{ $item->productVariant->color->name }}
                                                            <span
                                                                style="display:inline-block; width:10px; height:10px; border-radius:3px; margin-left:4px;
                                                        background: {{ $item->productVariant->color->hex_code }};">
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                        <br>
                                                        Size : {{ $item->productVariant->size->name ?? '-' }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="column-3 stext-105 cl3">
                                                <span class="price-box">${{ number_format($finalProductPrice, 2) }}</span>
                                            </td>
                                            <td class="column-4 text-center stext-105 cl2">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="column-5 text-right p-r-30 stext-105 cl1 font-weight-bold">
                                                ${{ rtrim(rtrim(number_format($totalItemPrice, 2), '0'), '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        {{-- Order Financial Summary --}}
                        <div class="flex-w flex-sb-t p-t-20">
                            <div class="col-md-6 p-l-0 p-r-3 m-b-20">
                                {{-- Note or Notice --}}
                                <div class="order-note-box p-all-15 bor10 bg-light">
                                    <div class="flex-w flex-m p-b-5">
                                        <i class="zmdi zmdi-info-outline cl1 m-r-8 fs-18"></i>
                                        <span class="stext-101 cl2 font-weight-bold">Need help with this order?</span>
                                    </div>
                                    <p class="stext-107 cl6">
                                        If you have any questions or require support regarding your parcel, please submit a
                                        ticket in the <a href="{{ route('customer.profile.my-tickets') }}"
                                            class="cl1 hov-cl2 trans-04 underline">Support Center</a>.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-5 p-l-0 p-l-20">
                                <div class="bor10 p-lr-25 p-t-20 p-b-25 bg-white">
                                    <h5 class="stext-110 cl2 font-weight-bold p-b-15 bor12">
                                        Order Summary
                                    </h5>

                                    @php
                                        $finalAmount = $order->order_final_amount ?? 0;
                                        $deliveryAmount = $order->delivery_amount ?? 0;
                                        $totalDiscount = $order->order_discount_amount ?? 0;

                                        $couponDiscount = $order->order_coupon_discount_amount ?? 0;
                                        $commonDiscount = $order->order_common_discount_amount ?? 0;
                                        $productDiscount = $order->order_total_products_discount_amount ?? 0;

                                        if ($totalDiscount == 0) {
                                            $totalDiscount = $couponDiscount + $commonDiscount + $productDiscount;
                                        }

                                    @endphp

                                    {{-- Subtotal (جمع قبل از تخفیف) --}}
                                    <div class="flex-w flex-sb-m p-t-12 p-b-8">
                                        <span class="stext-109 cl6">Subtotal:</span>
                                        <span class="stext-109 cl2">
                                            ${{ rtrim(rtrim(number_format($finalAmount, 2), '0'), '.') }}
                                        </span>
                                    </div>

                                    {{-- Product / Amazing Sale Discount --}}
                                    @if ($productDiscount > 0)
                                        <div class="flex-w flex-sb-m p-b-8">
                                            <span class="stext-109 cl6">Product Discount:</span>
                                            <span class="stext-109 text-danger">
                                                -${{ rtrim(rtrim(number_format($productDiscount, 2), '0'), '.') }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Coupon Discount --}}
                                    @if ($couponDiscount > 0)
                                        <div class="flex-w flex-sb-m p-b-8">
                                            <span class="stext-109 cl6">Coupon Discount:</span>
                                            <span class="stext-109 text-danger">
                                                -${{ rtrim(rtrim(number_format($couponDiscount, 2), '0'), '.') }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Common Discount --}}
                                    @if ($commonDiscount > 0)
                                        <div class="flex-w flex-sb-m p-b-8">
                                            <span class="stext-109 cl6">Special Discount:</span>
                                            <span class="stext-109 text-danger">
                                                -${{ rtrim(rtrim(number_format($commonDiscount, 2), '0'), '.') }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Shipping Cost --}}
                                    <div class="flex-w flex-sb-m p-b-12 bor12">
                                        <span class="stext-109 cl6">Shipping:</span>
                                        <span class="stext-109 cl2">
                                            {{ $deliveryAmount > 0 ? '$' . rtrim(rtrim(number_format($deliveryAmount, 2), '0'), '.') : 'Free' }}
                                        </span>
                                    </div>

                                    {{-- Grand Total --}}
                                    <div class="flex-w flex-sb-m p-t-15">
                                        <span class="mtext-101 cl2 font-weight-bold">Grand Total:</span>
                                        <span class="mtext-110 cl1 font-weight-bold">
                                            ${{ rtrim(rtrim(number_format($finalAmount, 2), '0'), '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
