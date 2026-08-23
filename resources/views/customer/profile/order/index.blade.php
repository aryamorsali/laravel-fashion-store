@extends('customer.layouts.app')

@section('head-tag')
    <title>Profile</title>

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


        .orders-header-icon {
            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: 100%;
        }

        .order-item {
            width: 100%;
        }

        .order-item-header {
            margin-bottom: 22px;
        }

        .order-item-body {
            min-height: 95px;
        }

        .order-products-preview {
            flex: 0 0 230px;
            max-width: 230px;
            align-items: center;
        }

        .order-product-thumb {
            width: 90px;
            height: 105px;
            margin-right: 8px;

            display: block;
            overflow: hidden;

            background-color: #f5f5f5;
        }

        .order-product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .order-more-products {
            width: 65px;
            height: 65px;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #717fe0;
            background-color: #f5f5f5;

            font-family: Poppins-Regular, sans-serif;
            font-size: 13px;
        }

        .order-summary {
            flex: 1;
            padding: 0 20px;
        }

        .order-summary-row {
            min-width: 180px;
            margin-bottom: 8px;
        }

        .order-action {
            margin-left: auto;
        }

        .order-details-btn {
            min-width: 125px;
            height: 38px;
            padding: 0 15px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            color: #717fe0;
            background-color: #fff;

            border: 1px solid #717fe0;
            border-radius: 2px;

            font-family: Poppins-Medium, sans-serif;
            font-size: 12px;
            text-transform: uppercase;

            transition: all 0.25s ease;
        }

        .order-details-btn:hover {
            color: #fff;
            background-color: #717fe0;
        }

        .order-status,
        .payment-status {
            font-family: Poppins-Regular, sans-serif;
            font-size: 12px;
            text-transform: capitalize;
        }

        .order-status i {
            font-size: 8px;
        }

        .order-status-awaiting_confirmation,
        .order-status-not_checked {
            color: #f0ad4e;
        }

        .order-status-confirmed {
            color: #26a65b;
        }

        .order-status-confirmed {
            color: #28a745;
        }

        .order-status-canceled,
        .order-status-returned,
        .order-status-not_confirmed {
            color: #e65540;
        }

        .payment-paid {
            color: #28a745;
        }

        .payment-unpaid,
        .payment-failed {
            color: #e65540;
        }

        .payment-returned {
            color: #f0ad4e;
        }

        .order-footer {
            margin-top: 22px;
            padding-top: 15px;

            border-top: 1px dashed #ddd;
        }

        .orders-empty-icon {
            color: #717fe0;
            font-size: 55px;
        }


        @media (max-width: 767.98px) {
            .order-item-header {
                display: block;
            }

            .order-date {
                margin-top: 12px;
            }

            .order-item-body {
                display: block;
            }

            .order-products-preview {
                max-width: 100%;
                margin-bottom: 18px;
            }

            .order-summary {
                padding: 0;
            }

            .order-summary-row {
                min-width: 100%;
            }

            .order-action {
                width: 100%;
                margin-top: 18px;
            }

            .order-details-btn {
                width: 100%;
            }

            .order-footer {
                display: block;
            }

            .order-footer>a {
                display: block;
                margin-top: 12px;
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

                <span class="stext-109 cl4">
                    My Orders
                </span>
            </div>

            <div class="row">

                {{-- Profile Sidebar --}}
                @include('customer.layouts.partials.profile-sidebar')

                {{-- Main Content --}}
                <div class="col-lg-9 col-md-8">
                    <div class="bor10 p-lr-40 p-t-35 p-b-40">

                        {{-- Header --}}
                        <div class="flex-w flex-sb-m p-b-25 bor12">
                            <div>
                                <h3 class="mtext-111 cl2 p-b-8">
                                    My Orders
                                </h3>

                                <p class="stext-109 cl6">
                                    View and track your recent orders.
                                </p>
                            </div>

                            <div class="orders-header-icon">
                                <i class="zmdi zmdi-shopping-basket"></i>
                            </div>
                        </div>

                        {{-- Orders --}}
                        @forelse ($orders as $order)
                            <div class="order-item p-t-25 p-b-25 bor12">

                                {{-- Order Top --}}
                                <div class="flex-w flex-sb-m order-item-header">
                                    <div>
                                        <span class="stext-109 cl6">
                                            Order number
                                        </span>

                                        <h5 class="stext-110 cl2 p-t-5">
                                            #{{ $order->id }}
                                        </h5>
                                    </div>

                                    <div class="order-date">
                                        <span class="stext-109 cl6">
                                            Order date
                                        </span>

                                        <p class="stext-110 cl2 p-t-5">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Order Body --}}
                                <div class="order-item-body flex-w flex-t">

                                    {{-- Product Preview --}}
                                    <div class="order-products-preview flex-w">
                                        @foreach ($order->orderItems->take(3) as $item)
                                            @if ($item->productVariant->product)
                                                <a href="{{ route('customer.market.product', [$item->productVariant->product, 'variant' => $item->product_variant_id]) }}"
                                                    class="order-product-thumb">
                                                    <img src="{{ asset($item->productVariant->product->image['indexArray']['main']) }}"
                                                        alt="{{ $item->productVariant->product->name }}">
                                                </a>
                                            @endif
                                        @endforeach

                                        @if ($order->orderItems->count() > 3)
                                            <span class="order-more-products">
                                                +{{ $order->orderItems->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Order Summary --}}
                                    <div class="order-summary">
                                        <div class="order-summary-row">
                                            <span class="stext-109 cl6">
                                                Items :
                                            </span>

                                            <span class="stext-109 cl2 mx-3">
                                                {{ $order->orderItems->sum('quantity') }}
                                            </span>
                                        </div>

                                        <div class="order-summary-row">
                                            <span class="stext-109 cl6">
                                                Total :
                                            </span>

                                            <strong class="stext-105 cl1 mx-3">
                                                ${{ rtrim(rtrim(number_format($order->order_final_amount, 2), '0'), '.') }}
                                            </strong>
                                        </div>

                                        <div class="order-summary-row">
                                            <span class="stext-109 cl6">
                                                Order Status :
                                            </span>

                                            <span class="order-status order-status-{{ $order->order_status }} mx-3">
                                                <i class="fa fa-circle m-r-5"></i>
                                                {{ str_replace('_', ' ', ucfirst($order->order_status)) }}
                                            </span>
                                        </div>

                                        <div class="order-summary-row">
                                            <span class="stext-109 cl6">
                                                Payment Status :
                                            </span>

                                            @if ($order->payment_status === 'paid')
                                                <span class="payment-status payment-paid mx-3">
                                                    Paid
                                                </span>
                                            @else
                                                <span class="payment-status payment-unpaid mx-3">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            @endif
                                        </div>

                                    </div>

                                    {{-- Action --}}
                                    <div class="order-action">
                                        <a href="#" class="order-details-btn">
                                            View Details
                                        </a>
                                    </div>
                                </div>


                            </div>
                        @empty
                            {{-- Empty State --}}
                            <div class="orders-empty-state text-center p-t-50 p-b-40">
                                <div class="orders-empty-icon">
                                    <i class="zmdi zmdi-shopping-basket"></i>
                                </div>

                                <h4 class="mtext-105 cl2 p-t-20 p-b-10">
                                    You have no orders yet
                                </h4>

                                <p class="stext-107 cl6 p-b-30">
                                    Your recent orders will appear here.
                                </p>

                                <a href="{{ route('customer.shop.index') }}"
                                    class="flex-c-m stext-101 cl0 size-116 bg1 bor2 hov-btn1 p-lr-15 trans-04 m-lr-auto">
                                    Start Shopping
                                </a>
                            </div>
                        @endforelse

                        {{-- Pagination --}}
                        @if ($orders->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->onEachSide(1)->links('vendor.pagination.custom') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
