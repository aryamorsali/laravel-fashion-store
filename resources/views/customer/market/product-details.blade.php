@extends('customer.layouts.app')

@section('head-tag')
    <title>Product Detail</title>

    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/MagnificPopup/magnific-popup.css') }}">
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <style>
        .addBtn {}

        /* ===== COLOR ===== */

        .color-option {
            margin-right: 8px;
            margin-bottom: 8px;

            position: relative;
        }

        /* hide inputs */
        .color-option input,
        .size-option input {
            display: none;
        }

        .swatch {
            width: 60px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
            cursor: pointer;
            transition: .15s;
            display: inline-block;
        }

        .color-option:hover .swatch {
            transform: scale(1.08);
        }

        .color-option input:checked+.swatch {
            border: 3px solid rgb(113, 127, 224);
        }

        .color-option.disabled {
            pointer-events: none;
            opacity: 0.35;
            cursor: not-allowed;
        }

        .color-option.disabled::before {
            content: 'X';
            color: #ffffff;
            position: absolute;
            top: 42%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
        }



        /* ===== SIZE ===== */

        .size-option {
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .size-box {
            width: 60px;
            height: 34px;
            padding-top: 6px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #e5e5e5;
            cursor: pointer;
            font-size: 14px;
            transition: .15s;
            display: inline-block;
        }

        .size-option:hover .size-box {
            border-color: #000;
        }

        .size-option input:checked+.size-box {
            border: 3px solid rgb(113, 127, 224);
        }

        .size-option input:disabled+.size-box {
            opacity: .35;
            cursor: not-allowed;
        }


        /* استایل دکمه‌ها تعداد*/
        .btn-qty {
            color: #4c4c4c;
            border: none;
            padding: 0 18px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.4s;
            height: 40px;
        }

        .btn-qty:hover {
            border-radius: 2px;
            background-color: #717fe0;
            color: #fff;
        }

        .btn-qty:active {
            background-color: #717fe0;

        }

        /* استایل input */
        .qty-input {
            background-color: #f8f9fa;
            width: 50px;
            text-align: center;
            border: none;
            outline: none;
            font-size: 16px;
            height: 40px;
        }



        /* استایل مشخصات محصول */
        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .old-price {
            font-size: 15px;
            color: #9b9b9b;
            text-decoration: line-through;
        }

        .new-price {
            font-size: 24px;
            font-weight: 500;
            color: #111;
        }

        .price-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sold-count {
            font-size: 14px;
            color: #777;
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rating i {
            color: #f5a623;
            font-size: 14px;
        }

        .rating-number {
            font-size: 14px;
            color: #333;
        }

        .dashed-divider {
            margin-top: 14px;
            border: none;
            border-top: 1px dashed #ddd;
        }



        /* span تخفیف شگفت انگیز */
        .badge-amazing {
            position: absolute;
            top: 10px;
            right: 70px;
            background: #e53935;
            color: #fff;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            z-index: 2;
        }


        /* amazingSale timer css */
        .badge-amazing-slider {
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

        .old-price-slider {
            color: #777;
            font-size: 0.9em;
            text-decoration: line-through;
            margin-right: 6px;
        }

        .new-price-slider {
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
    @include('admin.alerts.toast.success')
    @include('admin.alerts.toast.error')

    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="index.html" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>
            @if ($product->productCategory)
                @if ($product->productCategory->parent)
                    <a href="#" class="stext-109 cl8 hov-cl1 trans-04">
                        {{ $product->productCategory->parent->name }}
                        <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                    </a>
                @endif

                <a href="#" class="stext-109 cl8 hov-cl1 trans-04">
                    {{ $product->productCategory->name }}
                    <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
                </a>
            @endif

            <span class="stext-109 cl4">
                {{ $product->name }}
            </span>
        </div>
    </div>

    <!-- Product Detail -->
    <section class="sec-product-detail bg0 p-t-65 p-b-60">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-7 p-b-30">
                    <div class="p-l-25 p-r-30 p-lr-0-lg">
                        <div class="wrap-slick3 flex-sb flex-w">
                            <div class="wrap-slick3-dots"></div>
                            <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                            <div class="slick3 gallery-lb">
                                @php
                                    $imageGallery = $product->images;
                                    $images = collect();
                                    $images->push($product->image);
                                    foreach ($imageGallery as $image) {
                                        $images->push($image->image);
                                    }
                                @endphp
                                @foreach ($images as $key => $image)
                                    <div class="item-slick3" data-thumb="{{ asset($image['indexArray']['small']) }}">
                                        <div class="wrap-pic-w pos-relative">

                                            <img src="{{ asset($image['indexArray']['large']) }}"
                                                alt="{{ $product->name . '_' . ($key + 1) }}">

                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                                href="{{ asset($image['indexArray']['large']) }}"
                                                data-lightbox="gallery-product">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 p-b-30">
                    <div class="p-r-50 p-t-5 p-lr-0-lg">
                        <h4 class="mtext-105 cl2 js-name-detail p-b-14" style="font-size: 26px">
                            {{ $product->name }}
                        </h4>
                        <div class="price-row">
                            <div class="price-left">
                                <span class="old-price"></span>
                                <span class="new-price"></span>

                            </div>
                            <div>
                                <span class="badge-amazing"></span>
                                <span class="amazing-timer"></span>

                            </div>


                            <div class="price-right">
                                <span class="sold-count">{{ $product->total_sold ?? 0 }} Sold</span>

                                <div class="rating">
                                    <i class="fa fa-star"></i>
                                    <span class="rating-number">4.5</span>
                                </div>
                            </div>
                        </div>

                        <hr class="dashed-divider">

                        <!--  -->
                        <div class="p-t-20">

                            @if ($product->variants->count() != 0)
                                <div class="card border-0 shadow-sm p-4 rounded-4">

                                    <!-- COLOR -->

                                    <div id="color-div" class="mb-4">
                                        <p class="text-muted small mb-3">
                                            Selected color: <span id="selected-color-text"
                                                class="fw-semibold text-dark"></span>
                                        </p>

                                        <div class="d-flex flex-wrap" id="color-options">
                                        </div>
                                    </div>

                                    <!-- SIZE -->
                                    <div id="size-wrapper">
                                        <div id="size-div" class="mb-3 d-flex justify-content-between align-items-center">
                                            <p class="text-muted small mb-1">
                                                Selected size: <span id="selected-size-text"
                                                    class="fw-semibold text-dark"></span>
                                            </p>

                                            <a href="#" class="small text-decoration-underline text-muted">
                                                Size chart
                                            </a>
                                        </div>

                                        <div class="d-flex flex-wrap" id="size-options"></div>
                                    </div>
                                    <div class="mt-2">
                                        <p id="stock" class="text-danger small">
                                            Only 5 items left
                                        </p>
                                    </div>

                                    <div id="totalDiv" class="mt-3">
                                        <p class="text-dark" style="font-size: 18px">Total: $<span id="totalPrice">351.09</span></p>

                                    </div>

                                </div>
                            @endif


                            <div class="d-flex align-items-center justify-content-center mt-5" style="gap:14px;">
                                <!-- quantity -->
                                <div class="d-flex align-items-center border overflow-hidden qty-wrapper"
                                    style="width: max-content; border-radius: 2px;">
                                    <button type="button" class="btn-qty minus">−</button>
                                    <input type="number" value="1" min="1" id="num-product"
                                        class="qty-input mtext-104 cl3 txt-center">
                                    <button type="button" class="btn-qty plus">+</button>
                                </div>


                                <!-- add to cart -->
                                <form action="{{ route('customer.sales-process.add-to-cart') }}" method="POST">
                                    @csrf

                                    <input type="hidden" name="variant_id" id="variant_id">
                                    <input type="hidden" name="quantity" id="quantity">

                                    <button id="add-to-cart"
                                        class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                        Add to cart
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!--  -->
                        <div class="flex-w flex-m p-l-100 p-t-40 respon7">
                            <div class="flex-m bor9 p-r-10 m-r-11">
                                <a href="#"
                                    class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 js-addwish-detail tooltip100"
                                    data-tooltip="Add to Wishlist">
                                    <i class="zmdi zmdi-favorite"></i>
                                </a>
                            </div>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Facebook">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Twitter">
                                <i class="fa fa-twitter"></i>
                            </a>

                            <a href="#" class="fs-14 cl3 hov-cl1 trans-04 lh-10 p-lr-5 p-tb-2 m-r-8 tooltip100"
                                data-tooltip="Google Plus">
                                <i class="fa fa-google-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bor10 m-t-50 p-t-43 p-b-40">
                <!-- Tab01 -->
                <div class="tab01">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item p-b-10">
                            <a class="nav-link active" data-toggle="tab" href="#description"
                                role="tab">Description</a>
                        </li>
                        @if ($product->attributeValues->isNotEmpty())
                            <li class="nav-item p-b-10">
                                <a class="nav-link" data-toggle="tab" href="#information" role="tab">Additional
                                    information</a>
                            </li>
                        @endif

                        <li class="nav-item p-b-10">
                            <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews
                                ({{ $product->activeComments()->count() }})</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content p-t-43">
                        <!-- - -->
                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                            <div class="how-pos2 p-lr-15-md">
                                <p class="stext-102 cl6">
                                    {!! $product->description !!}
                                </p>
                            </div>
                        </div>

                        <!-- - -->
                        <div class="tab-pane fade" id="information" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                    <ul class="p-lr-28 p-lr-15-sm">
                                        @foreach ($product->attributeValues as $value)
                                            @if ($value->productAttribute)
                                                <li class="flex-w flex-t p-b-7">
                                                    <span class="stext-102 cl3 size-205">
                                                        {{ $value->productAttribute->name }}
                                                    </span>

                                                    <span class="stext-102 cl6 size-206">
                                                        {{ $value->value }} {{ $value->productAttribute->unit }}
                                                    </span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- - -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="row">
                                <div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
                                    <div class="p-b-30 m-lr-15-sm">
                                        <!-- Review -->


                                        <div id="comments-container">
                                            @foreach ($approvedComments as $comment)
                                                <div class="flex-w flex-t p-b-50">

                                                    {{-- Parent Avatar --}}
                                                    <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                                        <img src="{{ asset($comment->user->profile_photo_path ?? 'images/users/default-avatar.png') }}"
                                                            alt="avatar">
                                                    </div>

                                                    {{-- Parent Content --}}
                                                    <div class="size-207">

                                                        {{-- Name + Rating --}}
                                                        <div class="flex-w flex-sb-m p-b-17">
                                                            <span class="mtext-107 cl2 p-r-20">
                                                                {{ $comment->user->full_name ?? 'ناشناس' }}
                                                            </span>

                                                            <span class="fs-18 cl11">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <i
                                                                        class="zmdi zmdi-star{{ $i <= $comment->rating ? '' : '-outline' }}"></i>
                                                                @endfor
                                                            </span>
                                                        </div>

                                                        {{-- Comment Body --}}
                                                        <p class="stext-102 cl6">
                                                            {{ $comment->body }}
                                                        </p>


                                                        {{-- Replies --}}
                                                        @foreach ($comment->children as $childComment)
                                                            @if ($childComment->approved)
                                                                <div class="flex-w flex-t p-t-30"
                                                                    style="padding-left: 50px;">

                                                                    {{-- Reply Avatar --}}
                                                                    <div
                                                                        class="wrap-pic-s size-108 bor0 of-hidden m-r-18 m-t-6">
                                                                        <img src="{{ asset($childComment->user->profile_photo_path ?? 'images/users/default-avatar.png') }}"
                                                                            alt="avatar">
                                                                    </div>

                                                                    {{-- Reply Content --}}
                                                                    <div class="size-207">
                                                                        <span class="mtext-107 cl2 p-r-20">
                                                                            {{ $childComment->user->full_name ?? 'ناشناس' }}
                                                                        </span>

                                                                        <p class="stext-102 cl6 p-t-10">
                                                                            {{ $childComment->body }}
                                                                        </p>
                                                                    </div>

                                                                </div>
                                                            @endif
                                                        @endforeach

                                                    </div>

                                                </div>
                                            @endforeach
                                        </div>
                                        @if ($approvedComments->hasMorePages())
                                            <div class="text-center p-b-30">
                                                <button id="load-more-comments"
                                                    data-next-page="{{ $approvedComments->currentPage() + 1 }}"
                                                    class="btn btn-primary py-2">
                                                    see more commets
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Add review -->
                                        <form action="{{ route('customer.market.add-comment', $product) }}"
                                            method="post" class="w-full">
                                            @csrf
                                            <h5 class="mtext-108 cl2 p-b-7">
                                                Add a review
                                            </h5>
                                            <p class="stext-102 cl6">
                                                Your email address will not be published. Required fields are marked *
                                            </p>

                                            <div class="flex-w flex-m p-t-50">
                                                <span class="stext-102 cl3 m-r-16">
                                                    Your Rating
                                                </span>

                                                <span class="wrap-rating fs-18 cl11 pointer">
                                                    <input class="dis-none" type="radio" name="rating" id="star1"
                                                        value="1">
                                                    <label for="star1"
                                                        class="item-rating pointer zmdi zmdi-star-outline"></label>

                                                    <input class="dis-none" type="radio" name="rating" id="star2"
                                                        value="2">
                                                    <label for="star2"
                                                        class="item-rating pointer zmdi zmdi-star-outline"></label>

                                                    <input class="dis-none" type="radio" name="rating" id="star3"
                                                        value="3">
                                                    <label for="star3"
                                                        class="item-rating pointer zmdi zmdi-star-outline"></label>

                                                    <input class="dis-none" type="radio" name="rating" id="star4"
                                                        value="4">
                                                    <label for="star4"
                                                        class="item-rating pointer zmdi zmdi-star-outline"></label>

                                                    <input class="dis-none" type="radio" name="rating" id="star5"
                                                        value="5">
                                                    <label for="star5"
                                                        class="item-rating pointer zmdi zmdi-star-outline"></label>
                                                </span>
                                            </div>
                                            @error('rating')
                                                <div class="text-danger p-t-4 p-b-26"
                                                    style="font-size: 12px; font-weight: 400;">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror

                                            <div class="row p-b-25">
                                                <div class="col-12 p-b-5">
                                                    <label class="stext-102 cl3" for="review">Your review</label>
                                                    <textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="body">{{ old('body') }}</textarea>
                                                </div>
                                                @error('body')
                                                    <div class="text-danger"
                                                        style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                @enderror
                                            </div>

                                            <button
                                                class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
                                                Submit
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
            <span class="stext-107 cl6 p-lr-25">
                SKU: {{ $product->slug }}
            </span>


            @if ($product->productCategory)
                <span class="stext-107 cl6 p-lr-25">
                    Categories: @if ($product->productCategory->parent)
                        {{ $product->productCategory->parent->name }}
                    @endif, {{ $product->productCategory->name }}
                </span>
            @endif

        </div>
    </section>


    <!-- Related Products -->
    @if ($relatedProducts->count() > 0)
        <section class="sec-relate-product bg0 p-t-45 p-b-105">
            <div class="container">
                <div class="p-b-45">
                    <h3 class="ltext-106 cl5 txt-center">
                        Similar Products
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
                                    @foreach ($relatedProducts as $product)
                                        <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                                            <!-- Block2 -->
                                            <div class="block2">
                                                <div class="block2-pic hov-img0">
                                                    @php
                                                        // $variant
                                                        $variant = $product->variants
                                                            ->filter(function ($variant) {
                                                                return $variant->warehouseVariants->sum('stock') >
                                                                    $variant->warehouseVariants->sum('reserved');
                                                            })
                                                            ->sortByDesc(function ($variant) {
                                                                return $variant->orderItems->sum('quantity');
                                                            })
                                                            ->first();

                                                        $price = $variant?->price;
                                                        $finalPrice = $price;
                                                        $discount = null;

                                                        $activeAmazingSale =
                                                            $variant->amazingSale &&
                                                            $variant->amazingSale->is_active &&
                                                            $variant->amazingSale->start_date <= now() &&
                                                            $variant->amazingSale->end_date >= now()
                                                                ? $variant->amazingSale
                                                                : null;

                                                        if ($activeAmazingSale) {
                                                            $discount = $variant->amazingSale->percentage;
                                                            $finalPrice = $price - ($price * $discount) / 100;
                                                        }
                                                    @endphp

                                                    @if ($activeAmazingSale)
                                                        <span class="badge-amazing-slider">
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
                                                        <a href="product-detail.html"
                                                            class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                                            {{ $product->name }}
                                                        </a>

                                                        <span class="stext-105 cl3">
                                                            @if ($discount)
                                                                <del
                                                                    class="old-price-slider">${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}</del>
                                                                <span
                                                                    class="new-price-slider">${{ rtrim(rtrim(number_format($finalPrice, 2), '0'), '.') }}</span>
                                                            @else
                                                                ${{ rtrim(rtrim(number_format($price, 2), '0'), '.') }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <div class="block2-txt-child2 flex-r p-t-3">
                                                        <a href="#"
                                                            class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                                            <img class="icon-heart1 dis-block trans-04"
                                                                src="{{ asset('customer-assets/images/icons/icon-heart-01.png') }}"
                                                                alt="ICON">
                                                            <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                                                src="{{ asset('customer-assets/images/icons/icon-heart-02.png') }}"
                                                                alt="ICON">
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
            @if ($relatedProducts->count() >= 8)
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
@endsection

@section('script')
    <script src="{{ asset('customer-assets/vendor/select2/select2.min.js') }}">
        $(".js-select2").each(function() {
            $(this).select2({
                minimumResultsForSearch: 20,
                dropdownParent: $(this).next('.dropDownSelect2')
            });
        })
    </script>
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
        $('.js-addwish-b2, .js-addwish-detail').on('click', function(e) {
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

        // $('.js-addcart-detail').each(function() {
        //     var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
        //     $(this).on('click', function() {
        //         swal(nameProduct, "is added to cart !", "success");
        //     });
        // });
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
    {{-- مدیریت انتخاب واریانت مشتری به صورت زنده --}}
    <script>
        // =========================
        // DATA
        // =========================
        const variants = {{ Js::from($variantsForJs) }};
        const hasSellableVariant = {{ Js::from($hasSellableVariant) }};

        // =========================
        // ELEMENTS
        // =========================
        const colorBox = document.getElementById('color-options');
        const sizeBox = document.getElementById('size-options');
        const colorDiv = document.getElementById('color-div');
        const sizeWrapper = document.getElementById('size-wrapper');
        const colorText = document.getElementById('selected-color-text');
        const sizeText = document.getElementById('selected-size-text');
        const addToCartBtn = document.getElementById('add-to-cart');
        const oldPriceDisplay = document.querySelector('.old-price');
        const newPriceDisplay = document.querySelector('.new-price');
        const amazingBadge = document.querySelector('.badge-amazing');
        const amazingTimer = document.querySelector('.amazing-timer');
        const stockText = document.getElementById('stock');
        const qtyInput = document.querySelector('.qty-input');
        const btnPlus = document.querySelector('.btn-qty.plus');
        const btnMinus = document.querySelector('.btn-qty.minus');
        const totalPriceEl = document.getElementById('totalPrice');
        const totalDiv = document.getElementById('totalDiv');

        // =========================
        // STATE
        // =========================
        let selectedColor = null;
        let selectedSize = null;
        let currentVariant = null;

        let hasColor = variants.some(v => v.color_id);
        let hasSize = variants.some(v => v.size_id);

        // =========================
        // DEDUP COLORS
        // =========================
        const colors = hasColor ? [...new Map(
            variants.filter(v => v.color_id).map(v => [v.color_id, {
                id: v.color_id,
                name: v.color_name,
                hex: v.color_hex
            }])
        ).values()] : [];

        // =========================
        // HELPERS
        // =========================
        function isColorAvailable(id) {
            return variants.some(v => v.color_id === id && v.stock > 0);
        }

        function isSizeAvailable(sizeId, colorId) {
            return variants.some(v =>
                v.size_id === sizeId &&
                (!colorId || v.color_id === colorId) &&
                v.stock > 0
            );
        }

        function findActiveVariant() {
            if (!hasSellableVariant) return null;

            return variants.find(v =>
                (!hasColor || v.color_id === selectedColor) &&
                (!hasSize || v.size_id === selectedSize) &&
                v.stock > 0
            ) || null;
        }

        // =========================
        // RENDER COLORS
        // =========================
        function renderColors() {
            if (!hasColor) {
                if (colorDiv) colorDiv.style.display = 'none';
                return;
            }

            const sorted = [
                ...colors.filter(c => isColorAvailable(c.id)),
                ...colors.filter(c => !isColorAvailable(c.id))
            ];

            colorBox.innerHTML = sorted.map(c => `
        <label class="color-option ${!isColorAvailable(c.id) ? 'disabled' : ''}">
            <input type="radio" name="color" value="${c.id}" ${!isColorAvailable(c.id) ? 'disabled' : ''}>
            <span class="swatch" style="background:${c.hex}" title="${c.name}"></span>
        </label>`).join('');
        }

        // =========================
        // SHOW SIZES
        // =========================
        function showSizes(colorId = null) {

            if (!hasSize) {
                sizeWrapper.style.display = 'none';
                return;
            }

            const sizes = [...new Map(
                variants
                .filter(v => !hasColor || v.color_id === colorId)
                .map(v => [v.size_id, {
                    id: v.size_id,
                    name: v.size_name
                }])
            ).values()];

            //  جدا کردن موجود و ناموجود
            const availableSizes = sizes.filter(s => isSizeAvailable(s.id, colorId));
            const unavailableSizes = sizes.filter(s => !isSizeAvailable(s.id, colorId));

            //  موجودها اول
            const sortedSizes = [...availableSizes, ...unavailableSizes];

            sizeBox.innerHTML = sortedSizes.map(s => {

                const available = isSizeAvailable(s.id, colorId);

                return `
            <label class="size-option ${!available ? 'disabled' : ''}">
                <input type="radio" name="size" value="${s.id}" ${!available ? 'disabled' : ''}>
                <span class="size-box">${s.name}</span>
            </label> `;
            }).join('');

            const first = sizeBox.querySelector('input:not(:disabled)');

            if (first) {
                first.checked = true;
                selectedSize = Number(first.value);
                // بروز کردن متن selected size
                const variant = variants.find(v => v.size_id === selectedSize && (!colorId || v.color_id === colorId));
                if (variant) sizeText.innerText = variant.size_name;

            } else {
                selectedSize = null;
            }
        }

        // =========================
        // UPDATE TIMER (PRODUCT PAGE)
        // =========================
        function startAmazingTimer(timer, endDate) {
            if (!timer) return;

            if (timer._interval) {
                clearInterval(timer._interval);
            }

            if (!endDate) {
                timer.innerText = '';
                return;
            }

            const end = new Date(endDate).getTime();
            if (isNaN(end)) {
                timer.innerText = '';
                return;
            }

            function update() {
                const now = Date.now();
                const diff = end - now;

                if (diff <= 0) {
                    timer.innerText = 'Expired';
                    clearInterval(timer._interval);
                    return;
                }

                const s = Math.floor(diff / 1000);
                const m = Math.floor(s / 60);
                const h = Math.floor(m / 60);
                const d = Math.floor(h / 24);

                const hh = h % 24;
                const mm = m % 60;
                const ss = s % 60;

                if (d > 0) {
                    timer.innerText = `${d}d ${hh}h`;
                } else {
                    timer.innerText = `${hh}h ${mm}m ${ss}s`;
                }
            }

            update();
            timer._interval = setInterval(update, 1000);
        }

        // =========================
        // UPDATE PRICE
        // =========================

        function calculatePrice(variant) {
            if (!variant) {
                if (newPriceDisplay) {
                    newPriceDisplay.innerText = 'Out of stock';
                    newPriceDisplay.style.color = 'gray';
                    amazingBadge.style.display = 'none';
                    amazingTimer.style.display = 'none';
                    totalDiv.style.display = 'none';
                    stockText.style.display = 'none';
                }
                if (oldPriceDisplay) oldPriceDisplay.style.display = 'none';

                if (amazingTimer) {
                    amazingTimer.removeAttribute('data-end');
                    amazingTimer.innerText = '';
                    if (amazingTimer._interval) clearInterval(amazingTimer._interval);
                }

                return;
            }

            const base = Number(variant.price);
            const percent = Number(variant.percentage || 0);

            let final = base;

            if (percent > 0) {
                final = base * (1 - percent / 100);

                if (amazingBadge) {
                    amazingBadge.style.display = 'inline';
                    amazingBadge.innerText = percent + '% off';
                    amazingTimer.style.display = 'inline';

                    amazingTimer.setAttribute('data-end', variant.expire_at);

                    startAmazingTimer(amazingTimer, variant.expire_at); // ← این خط مهم
                }

                if (oldPriceDisplay) {
                    oldPriceDisplay.style.display = 'inline';
                    oldPriceDisplay.innerText = '$' + base.toFixed(2);
                }

            } else {
                if (amazingBadge && amazingTimer) {
                    amazingBadge.style.display = 'none';
                    amazingTimer.style.display = 'none';
                    amazingTimer.removeAttribute('data-end');

                    amazingTimer.innerText = '';
                    if (amazingTimer._interval) clearInterval(amazingTimer._interval);
                }

                if (oldPriceDisplay) oldPriceDisplay.style.display = 'none';
            }

            if (newPriceDisplay) {
                newPriceDisplay.innerText = '$' + final.toFixed(2);
            }
        }

        // =========================
        // UPDATE STOCK
        // =========================
        function updateStock(variant) {
            if (!stockText || !qtyInput) return;

            if (!variant) {
                stockText.innerText = 'Out of stock';
                qtyInput.max = 1;
                return;
            }

            const stock = Number(variant.stock);

            if (stock === 0) stockText.innerText = 'Out of stock';
            else if (stock <= 5) stockText.innerText = `Only ${stock} items left`;
            else stockText.innerText = 'In stock';

            const maxQty = Math.min(stock, 10);

            qtyInput.max = maxQty;

            if (qtyInput.value > maxQty) qtyInput.value = maxQty;
        }

        // =========================
        // TOTAL PRICE
        // =========================
        function updateTotalPrice(variant) {
            if (!variant) {
                totalPriceEl.innerText = '0';
                return;
            }

            const qty = Number(qtyInput.value) || 1;

            const base = Number(variant.price);
            const percent = Number(variant.percentage || 0);

            const final = percent ? base * (1 - percent / 100) : base;

            totalPriceEl.innerText = (final * qty).toFixed(2);
        }

        // =========================
        // CART BUTTON
        // =========================
        function updateCartButton() {
            if (!addToCartBtn) return;

            if (!currentVariant) {
                addToCartBtn.disabled = true;
                addToCartBtn.innerText = 'Out of stock';
                addToCartBtn.style.backgroundColor = '#ccc';
                addToCartBtn.style.cursor = 'not-allowed';
                sizeWrapper.style.display = 'none';
            } else {
                addToCartBtn.disabled = false;
                addToCartBtn.innerText = 'ADD TO CART';
                addToCartBtn.style.cursor = 'pointer';
            }
        }



        // =========================
        // MAIN UI REFRESH
        // =========================
        function refreshUI() {
            currentVariant = findActiveVariant();
            calculatePrice(currentVariant);
            updateStock(currentVariant);
            updateTotalPrice(currentVariant);
            updateCartButton();
        }

        // =========================
        // INIT
        // =========================
        document.addEventListener('DOMContentLoaded', () => {

            renderColors();

            // Default select color
            if (hasColor) {
                const first = colors.find(c => isColorAvailable(c.id));
                if (first) {
                    selectedColor = first.id;
                    const input = colorBox.querySelector(`input[value="${selectedColor}"]`);
                    if (input) input.checked = true;
                    colorText.innerText = first.name;
                    showSizes(selectedColor);
                }
            }

            // If only size exists
            if (!hasColor && hasSize) {
                showSizes();
            }
            if (!hasColor && !hasSize) {
                sizeWrapper.style.display = 'none';
            }

            currentVariant = findActiveVariant();
            refreshUI();

            // + / -
            if (btnPlus && btnMinus && qtyInput) {

                btnPlus.addEventListener('click', () => {
                    let qty = Number(qtyInput.value) || 1;
                    let max = Number(qtyInput.max) || 10;

                    if (qty < max) qtyInput.value = qty + 1;

                    refreshUI();
                });

                btnMinus.addEventListener('click', () => {
                    let qty = Number(qtyInput.value) || 1;
                    if (qty > 1) qtyInput.value = qty - 1;
                    refreshUI();
                });

                qtyInput.addEventListener('input', () => {
                    let qty = Number(qtyInput.value);
                    let max = Number(qtyInput.max) || 10;


                    if (qty > max) qtyInput.value = max;
                    if (qty < 1) qtyInput.value = 1;

                    refreshUI();
                });
            }
        });

        // =========================
        // EVENTS
        // =========================
        document.addEventListener('change', e => {

            if (e.target.name === 'color') {
                qtyInput.value = 1;

                selectedColor = Number(e.target.value);
                const clr = colors.find(c => c.id === selectedColor);
                if (clr) colorText.innerText = clr.name;

                showSizes(selectedColor);
                refreshUI();
            }

            if (e.target.name === 'size') {
                qtyInput.value = 1;

                selectedSize = Number(e.target.value);
                const variant = findActiveVariant();
                if (variant) sizeText.innerText = variant.size_name;
                refreshUI();
            }
        });

        // =========================
        // ADD TO CART
        // =========================
        addToCartBtn.addEventListener('click', function(e) {
            // مقدار variant انتخاب‌شده
            const variantId = currentVariant?.id;

            // مقدار تعداد انتخاب شده
            const qty = parseInt(document.getElementById('num-product').value);

            document.getElementById('variant_id').value = variantId;
            document.getElementById('quantity').value = qty;
        });
    </script>


    <!--===============================================================================================-->
    {{-- دکمه مشاهده بیشتر کامنت ها --}}
    <script>
        var btn = document.getElementById('load-more-comments');

        if (btn) {

            btn.addEventListener('click', function() {

                var page = btn.getAttribute('data-next-page');

                var url = window.location.pathname + '?page=' + page;
                fetch(url)
                    .then(function(response) {
                        console.log(response.text());

                        return response.text();

                    })
                    .then(function(html) {

                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');

                        var newComments = doc.querySelector('#comments-container').innerHTML;

                        document
                            .getElementById('comments-container')
                            .insertAdjacentHTML('beforeend', newComments);

                        btn.setAttribute('data-next-page', Number(page) + 1);

                        if (!doc.querySelector('#load-more-comments')) {
                            btn.remove();
                        }

                    });

            });

        }
    </script>


    <!--===============================================================================================-->
    {{-- amazingSale js timer --}}
    <script>
        document.querySelectorAll('.amazing-timer').forEach(timer => {

            if (!timer.dataset.end) return;

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
