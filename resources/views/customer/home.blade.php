@extends('customer.layouts.app')

@section('head-tag')
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/MagnificPopup/magnific-popup.css') }}">
    <style>
        .badge-amazing {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e53935;
            color: #fff;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            z-index: 2;
        }

        .old-price {
            color: #777;
            font-size: 0.9em;
            text-decoration: line-through;
            margin-right: 6px;
        }

        .new-price {
            color: #e53935;
            font-weight: 700;
            font-size: 1.05em;
        }

        .amazing-timer {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 4px;
            z-index: 2;
        }
    </style>
@endsection

@section('content')
    <!-- Slider -->
    <section class="section-slide">
        <div class="wrap-slick1">
            <div class="slick1">
                @foreach ($banners as $banner)
                    <div class="item-slick1" style="background-image: url('{{ asset($banner->image) }}');">
                        <div class="container h-full">
                            <div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
                                <div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
                                    <span class="ltext-101 cl2 respon2">
                                        {{ $banner->title ?? '' }}
                                    </span>
                                </div>

                                <div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
                                    <h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">
                                        {{ $banner->subtitle ?? '' }}
                                    </h2>
                                </div>

                                @if ($banner->button_text)
                                    <div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
                                        <a href="{{ $banner->button_url ?? '#' }}"
                                            class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">
                                            {{ $banner->button_text ?? '' }}
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- home boxes -->
    <div class="sec-banner bg0 p-t-95 p-b-30">
        <div class="container">
            <div class="row">
                @if (isset($boxes['top-left']))
                    <div class="col-md-6 p-b-30 m-lr-auto">
                        <!-- Block1 -->
                        <div class="block1 wrap-pic-w">
                            <img src="{{ asset($boxes['top-left']->image) }}" alt="IMG-BANNER">

                            <a href="product.html"
                                class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    <span class="block1-name ltext-102 trans-04 p-b-8">
                                        {{ $boxes['top-left']->title }}
                                    </span>

                                    <span class="block1-info stext-102 trans-04">
                                        {{ $boxes['top-left']->subtitle ?? '' }}
                                    </span>
                                </div>

                                <div class="block1-txt-child2 p-b-4 trans-05">
                                    <div class="block1-link stext-101 cl0 trans-09">
                                        Shop Now
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if (isset($boxes['top-right']))
                    <div class="col-md-6 p-b-30 m-lr-auto">
                        <!-- Block1 -->
                        <div class="block1 wrap-pic-w">
                            <img src="{{ asset($boxes['top-right']->image) }}" alt="IMG-BANNER">

                            <a href="product.html"
                                class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    <span class="block1-name ltext-102 trans-04 p-b-8">
                                        {{ $boxes['top-right']->title }}
                                    </span>

                                    <span class="block1-info stext-102 trans-04">
                                        {{ $boxes['top-right']->subtitle ?? '' }}
                                    </span>
                                </div>

                                <div class="block1-txt-child2 p-b-4 trans-05">
                                    <div class="block1-link stext-101 cl0 trans-09">
                                        Shop Now
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if (isset($boxes['center']))
                    <div class="col-md-6 col-lg-4 p-b-30 m-lr-auto">
                        <!-- Block1 -->
                        <div class="block1 wrap-pic-w">
                            <img src="{{ asset($boxes['center']->image) }}" alt="IMG-BANNER">

                            <a href="product.html"
                                class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    <span class="block1-name ltext-102 trans-04 p-b-8">
                                        {{ $boxes['center']->title }}
                                    </span>

                                    <span class="block1-info stext-102 trans-04">
                                        {{ $boxes['center']->subtitle ?? '' }}
                                    </span>
                                </div>

                                <div class="block1-txt-child2 p-b-4 trans-05">
                                    <div class="block1-link stext-101 cl0 trans-09">
                                        Shop Now
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if (isset($boxes['bottom-left']))
                    <div class="col-md-6 col-lg-4 p-b-30 m-lr-auto">
                        <!-- Block1 -->
                        <div class="block1 wrap-pic-w">
                            <img src="{{ asset($boxes['bottom-left']->image) }}" alt="IMG-BANNER">

                            <a href="product.html"
                                class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    <span class="block1-name ltext-102 trans-04 p-b-8">
                                        {{ $boxes['bottom-left']->title }}
                                    </span>

                                    <span class="block1-info stext-102 trans-04">
                                        {{ $boxes['bottom-left']->subtitle ?? '' }}
                                    </span>
                                </div>

                                <div class="block1-txt-child2 p-b-4 trans-05">
                                    <div class="block1-link stext-101 cl0 trans-09">
                                        Shop Now
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                @if (isset($boxes['bottom-right']))
                    <div class="col-md-6 col-lg-4 p-b-30 m-lr-auto">
                        <!-- Block1 -->
                        <div class="block1 wrap-pic-w">
                            <img src="{{ asset($boxes['bottom-right']->image) }}" alt="IMG-BANNER">

                            <a href="product.html"
                                class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
                                <div class="block1-txt-child1 flex-col-l">
                                    <span class="block1-name ltext-102 trans-04 p-b-8">
                                        {{ $boxes['bottom-right']->title }}
                                    </span>

                                    <span class="block1-info stext-102 trans-04">
                                        {{ $boxes['bottom-right']->subtitle ?? '' }}
                                    </span>
                                </div>

                                <div class="block1-txt-child2 p-b-4 trans-05">
                                    <div class="block1-link stext-101 cl0 trans-09">
                                        Shop Now
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>


    <!-- Product discounted -->
    @if ($amazingProducts->count() > 0)
        <section class="sec-product bg0 p-t-23 p-b-30">
            <div class="container">
                <div class="p-b-32">
                    <h3 class="ltext-103 cl5">
                        🔥 Limited Time Deals
                    </h3>
                </div>

                <!-- Tab01 -->
                <div class="tab01">
                    <!-- Tab panes -->
                    <div class="tab-content p-t-10">
                        <!-- - -->
                        <div class="tab-pane fade show active" id="best-seller" role="tabpanel">
                            <!-- Slide2 -->
                            <div class="wrap-slick2">
                                <div class="slick2">
                                    @foreach ($amazingProducts as $product)
                                        <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                                            <!-- Block2 -->
                                            <div class="block2">
                                                <div class="block2-pic hov-img0">

                                                    {{-- @php
                                                        $activeAmazingSale = $product->variants
                                                            ->pluck('amazingSale')
                                                            ->filter(
                                                                fn($sale) => $sale &&
                                                                    $sale->is_active &&
                                                                    $sale->start_date <= now() &&
                                                                    $sale->end_date >= now(),
                                                            )
                                                            ->sortByDesc('percentage')
                                                            ->first();
                                                    @endphp --}}


                                                    @php
                                                        $activeAmazingSale = $product->variants
                                                            // Only available variants
                                                            ->filter(
                                                                fn($v) => $v->warehouseVariants->sum('stock') >
                                                                    $v->warehouseVariants->sum('reserved'),
                                                            )
                                                            // Extract sales
                                                            ->pluck('amazingSale')
                                                            ->flatten()
                                                            // Only valid sales
                                                            ->filter(function ($sale) {
                                                                return $sale &&
                                                                    $sale->is_active &&
                                                                    $sale->start_date <= now() &&
                                                                    $sale->end_date >= now();
                                                            })
                                                            ->sortByDesc('percentage')
                                                            ->first();
                                                    @endphp



                                                    @if ($activeAmazingSale)
                                                        <span class="badge-amazing">
                                                            Up to {{ $activeAmazingSale->percentage }}% off
                                                        </span>

                                                        <span class="amazing-timer"
                                                            data-end="{{ $activeAmazingSale->end_date }}">
                                                        </span>
                                                    @endif


                                                    <img src="{{ asset($product->image['indexArray']['main']) }}"
                                                        alt="{{ $product->name }}">

                                                    <a href="{{ route('customer.market.product', $product) }}"
                                                        class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                                                        Shop Now
                                                    </a>
                                                </div>

                                                <div class="block2-txt flex-w flex-t p-t-14">
                                                    <div class="block2-txt-child1 flex-col-l ">
                                                        <a href="{{ route('customer.market.product', $product) }}"
                                                            class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                            {{ $product->name }}
                                                        </a>

                                                        {{-- @php
                                                            $variant = $product->variants->firstWhere(
                                                                'id',
                                                                $activeAmazingSale?->product_variant_id,
                                                            );

                                                            $price = $variant?->price ?? $product->base_price;

                                                            $discount = $activeAmazingSale?->percentage ?? 0;

                                                            $finalPrice = $discount
                                                                ? $price - ($price * $discount) / 100
                                                                : $price;
                                                        @endphp --}}


                                                        @php
                                                            $variant = $product->variants->firstWhere(
                                                                'id',
                                                                optional($activeAmazingSale)->product_variant_id,
                                                            );

                                                            $price = $variant?->price ?? $product->base_price;

                                                            $discount = $activeAmazingSale->percentage ?? 0;

                                                            $finalPrice = $discount
                                                                ? $price - ($price * $discount) / 100
                                                                : $price;
                                                        @endphp




                                                        <span class="stext-105 cl3">
                                                            @if ($discount)
                                                                <del
                                                                    class="old-price">${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}</del>
                                                                <span
                                                                    class="new-price">${{ rtrim(rtrim(number_format($finalPrice, 2), '0'), '.') }}</span>
                                                            @else
                                                                ${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <div class="block2-txt-child2 flex-r p-t-3">
                                                        <a href="#"
                                                            class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                                            <img class="icon-heart1 dis-block trans-04"
                                                                src="images/icons/icon-heart-01.png" alt="ICON">
                                                            <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                                                src="images/icons/icon-heart-02.png" alt="ICON">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($amazingProducts->count() >= 8)
                <div style="padding-right: 10rem" class="text-right pt-20">
                    <a href="#" style="color: white"
                        class="btn bg-dark stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 ">View
                        All Products</a>
                </div>
            @endif
        </section>
    @endif

    <!-- top Products -->
    @if ($topProducts->count() > 0)
        <section class="sec-product bg0 p-t-23 p-b-30">
            <div class="container">
                <div class="p-b-32">
                    {{-- <div class="d-flex justify-content-between align-items-center"> --}}

                    <h3 class="ltext-103 cl5">
                        🔥 Best Sellers
                    </h3>

                </div>

                <!-- Tab01 -->
                <div class="tab01">
                    <!-- Tab panes -->
                    <div class="tab-content p-t-10">
                        <!-- - -->
                        <div class="tab-pane fade show active" id="best-seller" role="tabpanel">
                            <!-- Slide2 -->
                            <div class="wrap-slick2">
                                <div class="slick2">
                                    @foreach ($topProducts as $product)
                                        <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                                            <!-- Block2 -->
                                            <div class="block2">
                                                <div class="block2-pic hov-img0">

                                                    @php
                                                        $availableVariants = $product->variants->filter(
                                                            fn($variant) => $variant->warehouseVariants->sum('stock') >
                                                                $variant->warehouseVariants->sum('reserved'),
                                                        );

                                                        $variant =
                                                            $availableVariants
                                                                ->sortByDesc(fn($v) => $v->orderItems->sum('quantity'))
                                                                ->first() ??
                                                            $product->variants
                                                                ->sortByDesc(fn($v) => $v->orderItems->sum('quantity'))
                                                                ->first();

                                                        $price = $variant?->price;
                                                        $finalPrice = $price;
                                                        $discount = null;
                                                        $activeAmazingSale = null;

                                                        $hasAmazingSale =
                                                            $variant &&
                                                            $variant->amazingSale &&
                                                            $variant->amazingSale->is_active &&
                                                            $variant->amazingSale->start_date <= now() &&
                                                            $variant->amazingSale->end_date >= now();

                                                        if ($hasAmazingSale) {
                                                            $activeAmazingSale = $variant->amazingSale;
                                                            $discount = $variant->amazingSale->percentage;
                                                            $finalPrice = $price - ($price * $discount) / 100;
                                                        }
                                                    @endphp

                                                    @if ($activeAmazingSale)
                                                        <span class="badge-amazing">
                                                            Up to {{ $activeAmazingSale->percentage }}% off
                                                        </span>

                                                        <span class="amazing-timer"
                                                            data-end="{{ $activeAmazingSale->end_date }}">
                                                        </span>
                                                    @endif

                                                    <img src="{{ asset($product->image['indexArray']['main']) }}"
                                                        alt="{{ $product->name }}">

                                                    <a href="{{ route('customer.market.product', $product) }}"
                                                        class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                                                        Shop Now
                                                    </a>
                                                </div>

                                                <div class="block2-txt flex-w flex-t p-t-14">
                                                    <div class="block2-txt-child1 flex-col-l ">
                                                        <a href="{{ route('customer.market.product', $product) }}"
                                                            class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                            {{ $product->name }}
                                                        </a>
                                                        <p>Number of sales : {{ $product->total_sold ?? 0 }}</p>

                                                        <span class="stext-105 cl3">
                                                            @if ($discount)
                                                                <del
                                                                    class="old-price">${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}</del>
                                                                <span
                                                                    class="new-price">${{ rtrim(rtrim(number_format($finalPrice, 2), '0'), '.') }}</span>
                                                            @else
                                                                ${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <div class="block2-txt-child2 flex-r p-t-3">
                                                        <a href="#"
                                                            class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                                            <img class="icon-heart1 dis-block trans-04"
                                                                src="images/icons/icon-heart-01.png" alt="ICON">
                                                            <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                                                src="images/icons/icon-heart-02.png" alt="ICON">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @if ($topProducts->count() >= 8)
                {{-- <!-- دکمه مشاهده همه -->
                <div class="text-center p-t-20">
                    <a href="#" class="btn stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 border">View All Products</a>
                </div> --}}
                <div style="padding-right: 10rem" class="text-right pt-20">
                    <a href="#" style="color: white"
                        class="btn bg-dark stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 ">View
                        All Products</a>
                </div>
                {{-- <div class="p-t-30">
                    <a href="#" class="btn btn-primary btn-block">View All Products</a>
                </div> --}}
            @endif

        </section>
    @endif


    <!-- latestProducts -->
    @if ($latestProducts->count() > 0)
        <section class="sec-product bg0 p-t-23 p-b-30">
            <div class="container">
                <div class="p-b-32">
                    <h3 class="ltext-103 cl5">
                        New Arrivals
                    </h3>
                </div>

                <!-- Tab01 -->
                <div class="tab01">
                    <!-- Tab panes -->
                    <div class="tab-content p-t-10">
                        <!-- - -->
                        <div class="tab-pane fade show active" id="best-seller" role="tabpanel">
                            <!-- Slide2 -->
                            <div class="wrap-slick2">
                                <div class="slick2">
                                    @foreach ($latestProducts as $product)
                                        <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                                            <!-- Block2 -->
                                            <div class="block2">
                                                <div class="block2-pic hov-img0">

                                                    @php
                                                        $activeAmazingSale = $product->variants
                                                            ->filter(
                                                                fn($v) => $v->warehouseVariants->sum('stock') >
                                                                    $v->warehouseVariants->sum('reserved'),
                                                            )
                                                            ->pluck('amazingSale')
                                                            ->flatten()
                                                            ->filter(
                                                                fn($sale) => $sale &&
                                                                    $sale->is_active &&
                                                                    $sale->start_date <= now() &&
                                                                    $sale->end_date >= now(),
                                                            )
                                                            ->sortByDesc('percentage')
                                                            ->first();
                                                    @endphp

                                                    @if ($activeAmazingSale)
                                                        <span class="badge-amazing">
                                                            Up to {{ $activeAmazingSale->percentage }}% off
                                                        </span>

                                                        <span class="amazing-timer"
                                                            data-end="{{ $activeAmazingSale->end_date }}">
                                                        </span>
                                                    @endif

                                                    <img src="{{ asset($product->image['indexArray']['main']) }}"
                                                        alt="{{ $product->name }}">

                                                    <a href="{{ route('customer.market.product', $product) }}"
                                                        class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
                                                        Shop Now
                                                    </a>
                                                </div>

                                                <div class="block2-txt flex-w flex-t p-t-14">
                                                    <div class="block2-txt-child1 flex-col-l ">
                                                        <a href="{{ route('customer.market.product', $product) }}"
                                                            class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                            {{ $product->name }}
                                                        </a>

                                                        @php
                                                            $variant =
                                                                $product->variants
                                                                    ->filter(
                                                                        fn($v) => $v->warehouseVariants->sum('stock') >
                                                                            $v->warehouseVariants->sum('reserved'),
                                                                    )
                                                                    ->firstWhere(
                                                                        'id',
                                                                        optional($activeAmazingSale)
                                                                            ->product_variant_id,
                                                                    ) ?? $product->variants->first();

                                                            $price = $variant->price;
                                                            $discount = $activeAmazingSale->percentage ?? 0;

                                                            $finalPrice = $discount
                                                                ? $price - ($price * $discount) / 100
                                                                : $price;
                                                        @endphp


                                                        <span class="stext-105 cl3">
                                                            @if ($discount)
                                                                <del
                                                                    class="old-price">${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}</del>
                                                                <span
                                                                    class="new-price">${{ rtrim(rtrim(number_format($finalPrice, 2), '0'), '.') }}</span>
                                                            @else
                                                                ${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <div class="block2-txt-child2 flex-r p-t-3">
                                                        <a href="#"
                                                            class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                                            <img class="icon-heart1 dis-block trans-04"
                                                                src="images/icons/icon-heart-01.png" alt="ICON">
                                                            <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                                                src="images/icons/icon-heart-02.png" alt="ICON">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($latestProducts->count() >= 8)
                <div style="padding-right: 10rem" class="text-right pt-20">
                    <a href="#" style="color: white"
                        class="btn bg-dark stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 ">View
                        All Products</a>
                </div>
            @endif
        </section>
    @endif


    <!-- Blog -->
    @if ($blogs->count() > 0)
        <section class="sec-blog bg0 p-t-50 p-b-90">
            <div class="container">
                <div class="p-b-66">
                    <h3 class="ltext-105 cl5 txt-center respon1">
                        Latest Blog Posts
                    </h3>
                </div>

                <div class="row">
                    @foreach ($blogs as $blog)
                        <div class="col-sm-6 col-md-4 p-b-40">
                            <div class="blog-item">
                                <div class="hov-img0">
                                    <a href="blog-detail.html">

                                        <img src="{{ asset($blog->image['blogArray'][$blog->image['currentImage']]) }}"
                                            alt="{{ $blog->title }}">
                                    </a>
                                </div>

                                <div class="p-t-15">
                                    <div class="stext-107 flex-w p-b-14">
                                        <span class="m-r-3">
                                            <span class="cl4">
                                                By
                                            </span>

                                            <span class="cl5">
                                                {{ $blog->user->full_name ?? '-' }}
                                            </span>
                                        </span>

                                        <span>
                                            <span class="cl4">
                                                on
                                            </span>

                                            <span class="cl5">
                                                {{ $blog->created_at }}
                                            </span>
                                        </span>
                                    </div>

                                    <h4 class="p-b-12">
                                        <a href="blog-detail.html" class="mtext-101 cl2 hov-cl1 trans-04">
                                            {{ $blog->title }}
                                        </a>
                                    </h4>

                                    <p class="stext-108 cl6">
                                        {!! $blog->summary !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif

@endsection

@section('script')
    <!--===============================================================================================-->
    <script src="{{ asset('customer-assets/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('customer-assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('customer-assets/vendor/slick/slick.min.js') }}"></script>
    <script src="{{ asset('customer-assets/js/slick-custom.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('customer-assets/vendor/parallax100/parallax100.js') }}"></script>
    <script>
        $('.parallax100').parallax100();
    </script>
    <!--===============================================================================================-->
    <script src="{{ asset('customer-assets/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
    <script>
        $('.gallery-lb').each(function() { // the containers for all your galleries
            $(this).magnificPopup({
                delegate: 'a', // the selector for gallery item
                type: 'image',
                gallery: {
                    enabled: true
                },
                mainClass: 'mfp-fade'
            });
        });
    </script>
    <!--===============================================================================================-->
    <script src="{{ asset('customer-assets/vendor/isotope/isotope.pkgd.min.js') }}"></script>
    <!--===============================================================================================-->
    <script src="{{ asset('customer-assets/vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $('.js-addwish-b2').on('click', function(e) {
            e.preventDefault();
        });

        $('.js-addwish-b2').each(function() {
            var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
            $(this).on('click', function() {
                swal(nameProduct, "is added to wishlist !", "success");

                $(this).addClass('js-addedwish-b2');
                $(this).off('click');
            });
        });

        $('.js-addwish-detail').each(function() {
            var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

            $(this).on('click', function() {
                swal(nameProduct, "is added to wishlist !", "success");

                $(this).addClass('js-addedwish-detail');
                $(this).off('click');
            });
        });

        /*---------------------------------------------*/

        $('.js-addcart-detail').each(function() {
            var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
            $(this).on('click', function() {
                swal(nameProduct, "is added to cart !", "success");
            });
        });
    </script>
    <!--===============================================================================================-->

    <script>
        $('.js-pscroll').each(function() {
            $(this).css('position', 'relative');
            $(this).css('overflow', 'hidden');
            var ps = new PerfectScrollbar(this, {
                wheelSpeed: 1,
                scrollingThreshold: 1000,
                wheelPropagation: false,
            });

            $(window).on('resize', function() {
                ps.update();
            })
        });
    </script>
    <!--===============================================================================================-->



    <script>
        // amazingSale js timer
        document.querySelectorAll('.amazing-timer').forEach(timer => {

            const endTime = new Date(timer.dataset.end).getTime();

            setInterval(() => {

                const now = Date.now();
                const diff = endTime - now;

                if (diff <= 0) {
                    timer.innerText = 'Expired';
                    return;
                }

                if (diff < 60 * 60 * 1000) {
                    timer.style.background = '#e53935';
                }

                // تبدیل میلی‌ ثانیه به واحدهای انسانی
                const seconds = Math.floor(diff / 1000);
                const minutes = Math.floor(seconds / 60);
                const hours = Math.floor(minutes / 60);
                const days = Math.floor(hours / 24);

                // باقی‌مانده‌ها
                const showHours = hours % 24;
                const showMinutes = minutes % 60;
                const showSeconds = seconds % 60;

                if (days > 0) {
                    timer.innerText = days + 'd ' + showHours + 'h';
                } else {
                    timer.innerText = showHours + 'h ' + showMinutes + 'm ' + showSeconds + 's';
                }

            }, 1000);
        });
    </script>
@endsection
