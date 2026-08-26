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

        /* Wishlist */
        .wishlist-header-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #f2f3ff;
            color: #717fe0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .wishlist-product-item {
            position: relative;
            gap: 20px;
        }

        .wishlist-product-image {
            width: 120px;
            height: 145px;
            flex-shrink: 0;
            overflow: hidden;
            background: #f7f7f7;
        }

        .wishlist-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .wishlist-product-image:hover img {
            transform: scale(1.07);
        }

        .wishlist-product-info {
            flex: 1;
            min-width: 180px;
            padding-top: 3px;
        }

        .wishlist-product-actions {
            min-width: 145px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
        }

        .wishlist-stock-status {
            font-family: Poppins-Regular, sans-serif;
            font-size: 13px;
        }

        .wishlist-stock-status.in-stock {
            color: #39a845;
        }

        .wishlist-stock-status.out-of-stock {
            color: #e65540;
        }

        .wishlist-empty-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f2f3ff;
            color: #717fe0;
            font-size: 38px;
        }

        @media (max-width: 767.98px) {
            .wishlist-product-item {
                gap: 15px;
            }

            .wishlist-product-image {
                width: 90px;
                height: 115px;
            }

            .wishlist-product-actions {
                position: static;
                width: 100%;
                margin-top: 15px;
                text-align: right;
            }
        }

        .wishlist-product-item {
            position: relative;
        }

        .wishlist-product-actions {
            position: absolute;
            right: 0;
            bottom: 25px;
        }

        .wishlist-remove-btn {
            min-width: 120px;
            height: 36px;
            padding: 0 14px;

            color: #e65540;
            background-color: #fff;

            border: 1px solid #e65540;
            border-radius: 3px;

            font-family: Poppins-Medium, sans-serif;
            font-size: 13px;
            text-transform: uppercase;

            cursor: pointer;
            transition: all 0.25s ease;
        }

        .wishlist-remove-btn:hover {
            color: #fff;
            background-color: #e65540;
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
                    Wishlist
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
                                    My Wishlist
                                </h3>

                                <p class="stext-109 cl6">
                                    Products you have saved for later.
                                </p>
                            </div>

                            <div class="wishlist-header-icon">
                                <i class="zmdi zmdi-favorite"></i>
                            </div>
                        </div>

                        {{-- Product list --}}
                        @forelse ($products as $product)
                            <div class="wishlist-product-item flex-w flex-t p-t-25 p-b-25 bor12">


                                @php
                                    $variant =
                                        $product->variants
                                            ->filter(
                                                fn($variant) => $variant->warehouseVariants->sum('stock') >
                                                    $variant->warehouseVariants->sum('reserved'),
                                            )
                                            ->sortBy(fn($v) => $v->price)
                                            ->first() ?? $product->variants->first();

                                    $price = $variant?->price;
                                    $finalPrice = $price;
                                    $discount = null;

                                    $activeAmazingSale = $variant->has_amazing_sale ? $variant->amazingSale : null;

                                    if ($activeAmazingSale) {
                                        $discount = $variant->discount_percentage;
                                        $finalPrice = $variant->final_price;
                                    }
                                @endphp

                                {{-- Product Image --}}
                                <div class="wishlist-product-image">
                                    <a
                                        href="{{ route('customer.market.product', [$product->slug, 'variant' => $variant->id]) }}">
                                        <img src="{{ asset($product->image['indexArray']['main']) }}"
                                            alt="{{ $product->name }}">
                                    </a>
                                </div>

                                {{-- Product Detail --}}
                                <div class="wishlist-product-info">
                                    <a href="{{ route('customer.market.product', [$product->slug, 'variant' => $variant->id]) }}"
                                        class="stext-110 cl2 hov-cl1 trans-04">
                                        {{ $product->name }}
                                    </a>

                                    @if ($product->productCategory)
                                        <p class="stext-107 cl6 p-t-6">
                                            {{ $product->productCategory->name }}
                                        </p>
                                    @endif

                                    <div class="p-t-10">
                                        @if ($activeAmazingSale)
                                            <span class="stext-105 cl3 text-decoration-line-through m-r-8 text-danger">
                                                ${{ rtrim(rtrim(number_format($finalPrice, 2), '0'), '.') }}
                                            </span>


                                            <span class="stext-105 cl1" style=" text-decoration: line-through;">
                                                ${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}
                                            </span>
                                        @else
                                            <span class="stext-105 cl1">
                                                ${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Optional: stock status --}}
                                    <div class="p-t-10">
                                        @if ($variant->availableStock())
                                            <span class="wishlist-stock-status in-stock">
                                                <i class="fa fa-check-circle m-r-5"></i>
                                                In Stock
                                            </span>
                                        @else
                                            <span class="wishlist-stock-status out-of-stock">
                                                <i class="fa fa-times-circle m-r-5"></i>
                                                Out of Stock
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="wishlist-product-actions">
                                    <form action="{{ route('customer.profile.my-favorites.delete', $product) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove from wishlist"
                                            aria-label="Remove {{ $product->name }} from wishlist"
                                            class="wishlist-remove-btn">
                                            Remove Like
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @empty
                            {{-- Empty Wishlist --}}
                            <div class="wishlist-empty-state text-center p-t-50 p-b-40">
                                <div class="wishlist-empty-icon">
                                    <i class="zmdi zmdi-favorite-outline"></i>
                                </div>

                                <h4 class="mtext-105 cl2 p-t-20 p-b-10">
                                    Your wishlist is empty
                                </h4>

                                <p class="stext-107 cl6 p-b-30">
                                    You have not added any products to your wishlist yet.
                                </p>

                                <a href="{{ route('customer.shop.index') }}"
                                    class="flex-c-m stext-101 cl0 size-116 bg1 bor2 hov-btn1 p-lr-15 trans-04 m-lr-auto">
                                    Continue Shopping
                                </a>
                            </div>
                        @endforelse

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->onEachSide(1)->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
