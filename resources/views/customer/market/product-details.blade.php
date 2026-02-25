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

        .color-option {
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .size-option {
            margin-right: 8px;
            margin-bottom: 8px;
        }

        /* ===== COLOR ===== */
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


        /* ===== SIZE ===== */

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
            font-size: 14px;
            color: #9b9b9b;
            text-decoration: line-through;
        }

        .new-price {
            font-size: 22px;
            font-weight: 700;
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

        /* سکشن رنگ اگر موجودی نداشتیم */
        .color-option.disabled {
            pointer-events: none;
            opacity: 0.4;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
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
                        {{-- <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                            {{ $product->name }}
                        </h4>

                        <span class="mtext-106 cl2">
                            ${{ rtrim(rtrim(number_format($product->base_price, 2), '0'), '.') }}
                        </span> --}}


                        <h4 class="mtext-105 cl2 js-name-detail" style="margin-bottom: 12px;">
                            {{ $product->name }}
                        </h4>
                        <div class="price-row">
                            <div class="price-left">

                                {{-- @php
                                    $price = $product?->variants?->activeAmazingSale?->percentage;
                                @endphp --}}

                                {{-- @if ($amazingSale)
                                    <span class="old-price">${{ rtrim(rtrim(number_format($product->base_price, 2), '0'), '.') }}</span>
                                    <span class="new-price">${{ rtrim(rtrim(number_format($product->base_price, 2), '0'), '.') }}</span>
                                @endif --}}

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
                                {{-- <div class="box">

                                    <!-- COLOR -->
                                    <p class="label">Color: <span class="value">Royal Brown</span></p>

                                    <div class="colors">
                                        <label>
                                            <input type="radio" name="color" class="color-input" checked>
                                            <div class="color-swatch" style="background:#5b4636"></div>
                                        </label>

                                        <label>
                                            <input type="radio" name="color" class="color-input">
                                            <div class="color-swatch" style="background:#d9d9d9"></div>
                                        </label>

                                        <label>
                                            <input type="radio" name="color" class="color-input">
                                            <div class="color-swatch" style="background:#3d5a80"></div>
                                        </label>

                                        <label>
                                            <input type="radio" name="color" class="color-input">
                                            <div class="color-swatch" style="background:#111"></div>
                                        </label>
                                    </div>

                                    <br><br>

                                    <!-- SIZE -->
                                    <div style="display:flex;justify-content:space-between;align-items:center">
                                        <p class="label">Size: <span class="value">8</span></p>
                                        <small style="color:#777; text-decoration:underline; cursor:pointer">Size
                                            chart</small>
                                    </div>

                                    <div class="sizes">
                                        <label>
                                            <input type="radio" name="size" class="size-input">
                                            <div class="size-box">6</div>
                                        </label>

                                        <label>
                                            <input type="radio" name="size" class="size-input" checked>
                                            <div class="size-box">8</div>
                                        </label>

                                        <label>
                                            <input type="radio" name="size" class="size-input">
                                            <div class="size-box">10</div>
                                        </label>

                                        <label>
                                            <input type="radio" name="size" class="size-input">
                                            <div class="size-box">14</div>
                                        </label>

                                        <label>
                                            <input type="radio" name="size" class="size-input" disabled>
                                            <div class="size-box">18</div>
                                        </label>
                                    </div>

                                </div> --}}

                                {{-- <div class="card border-0 shadow-sm p-4 rounded-4">
                                    <!-- COLOR -->
                                    <div class="mb-4">
                                        <p class="text-muted small mb-2">
                                            Color: <span style="font-size: 13px;" class="fw-semibold text-dark">Royal
                                                Brown</span>
                                        </p>

                                        <div class="d-flex flex-wrap">

                                            <label class="color-option">
                                                <input type="radio" name="color" checked>
                                                <span class="swatch" style="background:#5b4636"></span>
                                            </label>

                                            <label class="color-option">
                                                <input type="radio" name="color">
                                                <span class="swatch" style="background:#d9d9d9"></span>
                                            </label>

                                            <label class="color-option">
                                                <input type="radio" name="color">
                                                <span class="swatch" style="background:#3d5a80"></span>
                                            </label>

                                            <label class="color-option">
                                                <input type="radio" name="color">
                                                <span class="swatch" style="background:#111"></span>
                                            </label>

                                        </div>
                                    </div>
                                    <!-- SIZE -->
                                    <div class="mb-3 d-flex justify-content-between align-items-center">
                                        <p class="text-muted small mb-0">
                                            Size: <span class="fw-semibold text-dark">8</span>
                                        </p>

                                        <a href="#" class="small text-decoration-underline text-muted">
                                            Size chart
                                        </a>
                                    </div>

                                    <div class="d-flex flex-wrap">

                                        <label class="size-option">
                                            <input type="radio" name="size">
                                            <span class="size-box">6</span>
                                        </label>

                                        <label class="size-option">
                                            <input type="radio" name="size" checked>
                                            <span class="size-box">8</span>
                                        </label>

                                        <label class="size-option">
                                            <input type="radio" name="size">
                                            <span class="size-box">10</span>
                                        </label>

                                        <label class="size-option">
                                            <input type="radio" name="size">
                                            <span class="size-box">14</span>
                                        </label>

                                        <label class="size-option">
                                            <input type="radio" name="size">
                                            <span class="size-box">16</span>
                                        </label>

                                        <label class="size-option">
                                            <input type="radio" name="size" disabled>
                                            <span class="size-box">18</span>
                                        </label>

                                    </div>

                                </div> --}}

                                <div class="card border-0 shadow-sm p-4 rounded-4">

                                    <!-- COLOR -->

                                    <div id="color-div" class="mb-4">
                                        <p class="text-muted small mb-2">
                                            Selected color: <span id="selected-color-text"
                                                class="fw-semibold text-dark"></span>
                                        </p>

                                        <div class="d-flex flex-wrap" id="color-options">
                                        </div>
                                    </div>

                                    <!-- SIZE -->
                                    <div id="size-wrapper">
                                        <div id="size-div" class="mb-3 d-flex justify-content-between align-items-center">
                                            <p class="text-muted small mb-0">
                                                Selected size: <span id="selected-size-text"
                                                    class="fw-semibold text-dark"></span>
                                            </p>

                                            <a href="#" class="small text-decoration-underline text-muted">
                                                Size chart
                                            </a>
                                        </div>

                                        <div class="d-flex flex-wrap" id="size-options"></div>
                                    </div>

                                </div>
                            @endif


                            <div class="d-flex align-items-center justify-content-center mt-5" style="gap:14px;">
                                <!-- quantity -->
                                <div class="d-flex align-items-center border overflow-hidden qty-wrapper"
                                    style="width: max-content; border-radius: 2px;">
                                    <button type="button" class="btn-qty minus">−</button>
                                    <input type="number" value="1" min="1" max="5"
                                        class="qty-input mtext-104 cl3 txt-center">
                                    <button type="button" class="btn-qty plus">+</button>
                                </div>


                                <!-- add to cart -->
                                <button id="add-to-cart"
                                    class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                    Add to cart
                                </button>

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
                            <a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (1)</a>
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
                                        <div class="flex-w flex-t p-b-68">
                                            <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                                <img src="images/avatar-01.jpg" alt="AVATAR">
                                            </div>

                                            <div class="size-207">
                                                <div class="flex-w flex-sb-m p-b-17">
                                                    <span class="mtext-107 cl2 p-r-20">
                                                        Ariana Grande
                                                    </span>

                                                    <span class="fs-18 cl11">
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star"></i>
                                                        <i class="zmdi zmdi-star-half"></i>
                                                    </span>
                                                </div>

                                                <p class="stext-102 cl6">
                                                    Quod autem in homine praestantissimum atque optimum est, id deseruit.
                                                    Apud ceteros autem philosophos
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Add review -->
                                        <form class="w-full">
                                            <h5 class="mtext-108 cl2 p-b-7">
                                                Add a review
                                            </h5>

                                            <p class="stext-102 cl6">
                                                Your email address will not be published. Required fields are marked *
                                            </p>

                                            <div class="flex-w flex-m p-t-50 p-b-23">
                                                <span class="stext-102 cl3 m-r-16">
                                                    Your Rating
                                                </span>

                                                <span class="wrap-rating fs-18 cl11 pointer">
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <i class="item-rating pointer zmdi zmdi-star-outline"></i>
                                                    <input class="dis-none" type="number" name="rating">
                                                </span>
                                            </div>

                                            <div class="row p-b-25">
                                                <div class="col-12 p-b-5">
                                                    <label class="stext-102 cl3" for="review">Your review</label>
                                                    <textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="review"></textarea>
                                                </div>

                                                <div class="col-sm-6 p-b-5">
                                                    <label class="stext-102 cl3" for="name">Name</label>
                                                    <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="name"
                                                        type="text" name="name">
                                                </div>

                                                <div class="col-sm-6 p-b-5">
                                                    <label class="stext-102 cl3" for="email">Email</label>
                                                    <input class="size-111 bor8 stext-102 cl2 p-lr-20" id="email"
                                                        type="text" name="email">
                                                </div>
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
                SKU: JAK-01
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
    <section class="sec-relate-product bg0 p-t-45 p-b-105">
        <div class="container">
            <div class="p-b-45">
                <h3 class="ltext-106 cl5 txt-center">
                    Related Products
                </h3>
            </div>

            <!-- Slide2 -->
            <div class="wrap-slick2">
                <div class="slick2">
                    <div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">
                                <img src="{{ asset('customer-assets/images/product-07.jpg') }}" alt="IMG-PRODUCT">

                                <a href="#"
                                    class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 js-show-modal1">
                                    Quick View
                                </a>
                            </div>

                            <div class="block2-txt flex-w flex-t p-t-14">
                                <div class="block2-txt-child1 flex-col-l ">
                                    <a href="product-detail.html" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                        Esprit Ruffle Shirt
                                    </a>

                                    <span class="stext-105 cl3">
                                        $16.64
                                    </span>
                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                        <img class="icon-heart1 dis-block trans-04"
                                            src="{{ asset('customer-assets/images/icons/icon-heart-01.png') }}"
                                            alt="ICON">
                                        <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                            src="images/icons/icon-heart-02.png" alt="ICON">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal1 -->
    <div class="wrap-modal1 js-modal1 p-t-60 p-b-20">
        <div class="overlay-modal1 js-hide-modal1"></div>

        <div class="container">
            <div class="bg0 p-t-60 p-b-30 p-lr-15-lg how-pos3-parent">
                <button class="how-pos3 hov3 trans-04 js-hide-modal1">
                    <img src="images/icons/icon-close.png" alt="CLOSE">
                </button>

                <div class="row">
                    <div class="col-md-6 col-lg-7 p-b-30">
                        <div class="p-l-25 p-r-30 p-lr-0-lg">
                            <div class="wrap-slick3 flex-sb flex-w">
                                <div class="wrap-slick3-dots"></div>
                                <div class="wrap-slick3-arrows flex-sb-m flex-w"></div>

                                <div class="slick3 gallery-lb">
                                    <div class="item-slick3" data-thumb="images/product-detail-01.jpg">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="images/product-detail-01.jpg" alt="IMG-PRODUCT">

                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                                href="images/product-detail-01.jpg">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="item-slick3" data-thumb="images/product-detail-02.jpg">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="images/product-detail-02.jpg" alt="IMG-PRODUCT">

                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                                href="images/product-detail-02.jpg">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="item-slick3" data-thumb="images/product-detail-03.jpg">
                                        <div class="wrap-pic-w pos-relative">
                                            <img src="images/product-detail-03.jpg" alt="IMG-PRODUCT">

                                            <a class="flex-c-m size-108 how-pos1 bor0 fs-16 cl10 bg0 hov-btn3 trans-04"
                                                href="images/product-detail-03.jpg">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-5 p-b-30">
                        <div class="p-r-50 p-t-5 p-lr-0-lg">
                            <h4 class="mtext-105 cl2 js-name-detail p-b-14">
                                Lightweight Jacket
                            </h4>

                            <span class="mtext-106 cl2">
                                $58.79
                            </span>

                            <p class="stext-102 cl3 p-t-23">
                                Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare
                                feugiat.
                            </p>

                            <!--  -->
                            <div class="p-t-33">
                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-203 flex-c-m respon6">
                                        Size
                                    </div>

                                    <div class="size-204 respon6-next">
                                        <div class="rs1-select2 bor8 bg0">
                                            <select class="js-select2" name="time">
                                                <option>Choose an option</option>
                                                <option>Size S</option>
                                                <option>Size M</option>
                                                <option>Size L</option>
                                                <option>Size XL</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-203 flex-c-m respon6">
                                        Color
                                    </div>

                                    <div class="size-204 respon6-next">
                                        <div class="rs1-select2 bor8 bg0">
                                            <select class="js-select2" name="time">
                                                <option>Choose an option</option>
                                                <option>Red</option>
                                                <option>Blue</option>
                                                <option>White</option>
                                                <option>Grey</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-w flex-r-m p-b-10">
                                    <div class="size-204 flex-w flex-m respon6-next">
                                        <div class="wrap-num-product flex-w m-r-20 m-tb-10">
                                            <div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-minus"></i>
                                            </div>

                                            <input class="mtext-104 cl3 txt-center num-product" type="number"
                                                name="num-product" value="1">

                                            <div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
                                                <i class="fs-16 zmdi zmdi-plus"></i>
                                            </div>
                                        </div>

                                        <button
                                            class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
                                            Add to cart
                                        </button>
                                    </div>
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
            </div>
        </div>
    </div>
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
        document.querySelectorAll('.qty-wrapper').forEach(wrapper => {
            const input = wrapper.querySelector('.qty-input');
            const plus = wrapper.querySelector('.plus');
            const minus = wrapper.querySelector('.minus');

            const MAX = 5;
            const MIN = 1;

            plus.addEventListener('click', () => {
                let value = parseInt(input.value) || MIN;
                if (value < MAX) {
                    input.value = value + 1;
                }
            });

            minus.addEventListener('click', () => {
                let value = parseInt(input.value) || MIN;
                if (value > MIN) {
                    input.value = value - 1;
                }
            });

            // جلوگیری از تایپ عدد بیشتر از ۵
            input.addEventListener('input', () => {
                if (input.value > MAX) input.value = MAX;
                if (input.value < MIN) input.value = MIN;
            });
        });
    </script>

    <!--===============================================================================================-->
    {{-- مدیریت واریانت های محصول --}}
    <script>
        /* =========================
                          داده‌ها 
                        ========================= */
        const variants = {{ Js::from($variantsForJs) }};
        const hasSellableVariant = {{ Js::from($hasSellableVariant) }};
        /* =========================
           عناصر HTML
        ========================= */
        const colorBox = document.getElementById('color-options');
        const sizeBox = document.getElementById('size-options');
        const colorDiv = document.getElementById('color-div');
        const sizeWrapper = document.getElementById('size-wrapper');
        const sizeText = document.getElementById('selected-size-text');
        const colorText = document.getElementById('selected-color-text');
        const addToCartBtn = document.getElementById('add-to-cart');


        /* =========================
           وضعیت انتخاب
        ========================= */
        let selectedColor = null;
        let selectedSize = null;

        /* =========================
           آیا رنگ / سایز داریم؟
        ========================= */
        let hasColor = false;
        let hasSize = false;

        variants.forEach(v => {
            if (v.color_id) hasColor = true;
            if (v.size_id) hasSize = true;
        });
        /* =========================
           رنگ‌های یکتا
        ========================= */
        let colors = [];

        if (hasColor) {
            variants.forEach(v => {
                if (!v.color_id) return;

                const exists = colors.find(c => c.id === v.color_id);
                if (!exists) {
                    colors.push({
                        id: v.color_id,
                        name: v.color_name,
                        hex: v.color_hex
                    });
                }
            });
        }
        /* =========================
           رندر رنگ‌ها
        ========================= */
        function renderColors() {
            if (!hasColor) {
                colorDiv.style.display = 'none';
                return;
            }

            //  جدا کردن رنگ‌های موجود و ناموجود
            const availableColors = colors.filter(c => isColorAvailable(c.id));
            const disabledColors = colors.filter(c => !isColorAvailable(c.id));

            //  ترکیب نهایی: موجودها اول، ناموجودها بعد
            const sortedColors = [...availableColors, ...disabledColors];

            // ساخت HTML
            let html = '';
            sortedColors.forEach(c => {
                const disabled = !isColorAvailable(c.id);

                html += `<label class="color-option ${disabled ? 'disabled' : ''}">
                <input type="radio"
                       name="color"
                       value="${c.id}"
                       ${disabled ? 'disabled' : ''}>
                <span class="swatch"
                      style="background:${c.hex}"
                      title="${c.name}"></span>
            </label> `;
            });

            colorBox.innerHTML = html;

            // 4️⃣ انتخاب خودکار اولین رنگ موجود
            const firstAvailable = colorBox.querySelector(
                'input[name="color"]:not(:disabled)'
            );

            if (firstAvailable) {
                firstAvailable.checked = true;
                selectedColor = Number(firstAvailable.value);
                updateColorTextById(selectedColor);
                showSizes(selectedColor);
            }
        }

        /* =========================
           نمایش سایزها
        ========================= */
        function showSizes(colorId = null) {

            sizeBox.innerHTML = '';

            let list = variants.filter(v => {
                if (!v.size_id) return false;
                if (hasColor) return v.color_id === colorId;
                return true;
            });

            if (!list.length) {
                sizeWrapper.style.display = 'none';
                return;
            }

            sizeWrapper.style.display = 'block';

            // 1️⃣ جدا کردن سایزهای موجود و ناموجود
            const availableSizes = list.filter(v => v.stock > 0);
            const disabledSizes = list.filter(v => v.stock === 0);

            const sortedSizes = [...availableSizes, ...disabledSizes];

            // 2️⃣ ساخت HTML
            sortedSizes.forEach(v => {

                const disabled = v.stock === 0;

                sizeBox.innerHTML += `
            <label class="size-option ${disabled ? 'disabled' : ''}">
                <input type="radio"
                       name="size"
                       value="${v.size_id}"
                       ${disabled ? 'disabled' : ''}>
                <span class="size-box">${v.size_name}</span>
            </label>
        `;
            });

            // 3️⃣ انتخاب خودکار اولین سایز موجود
            const firstAvailable = sizeBox.querySelector(
                'input[name="size"]:not(:disabled)'
            );

            if (firstAvailable) {
                firstAvailable.checked = true;
                selectedSize = Number(firstAvailable.value);
                updateSizeText(
                    list.find(v => v.size_id === selectedSize)
                );
            }
        }


        /* =========================
           آپدیت متن سایز
        ========================= */
        function updateSizeText(variant) {
            if (!variant || !sizeText) return;
            sizeText.textContent = variant.size_name;
        }

        /* =========================
               آپدیت متن رنگ
        ========================= */
        function updateColorTextById(colorId) {
            const color = colors.find(c => c.id === colorId);
            if (!color || !colorText) return;
            colorText.textContent = color.name;
        }

        /* =========================
           رویداد تغییر رنگ
        ========================= */
        document.addEventListener('change', e => {
            if (e.target.name === 'color') {

                selectedColor = Number(e.target.value);
                selectedSize = null;

                updateColorTextById(selectedColor);

                showSizes(selectedColor);
            }
        });

        /* =========================
           رویداد تغییر سایز
        ========================= */
        document.addEventListener('change', e => {
            if (e.target.name === 'size') {
                selectedSize = Number(e.target.value);

                const variant = variants.find(v => {
                    if (hasColor) {
                        return v.color_id === selectedColor && v.size_id === selectedSize;
                    }
                    return v.size_id === selectedSize;
                });

                updateSizeText(variant);
                console.log('variant:', variant);
            }
        });


        /* =========================
           اجرای اولیه
        ========================= */
        document.addEventListener('DOMContentLoaded', () => {

            renderColors();

            if (hasColor && colors.length) {
                const firstAvailableColor = colors.find(c => isColorAvailable(c.id));

                if (firstAvailableColor) {
                    selectedColor = firstAvailableColor.id;

                    const firstColorRadio = colorBox.querySelector(
                        `input[value="${selectedColor}"]`
                    );

                    if (firstColorRadio) firstColorRadio.checked = true;

                    updateColorTextById(selectedColor);
                    showSizes(selectedColor);
                }

            } else if (hasSize) {
                showSizes();
            } else {
                sizeWrapper.style.display = 'none';
            }

            /* =========================
                 اگر موجودی نداشتیم
             ========================= */
            if (!hasSellableVariant) {

                // دکمه افزودن به سبد
                addToCartBtn.disabled = true;
                addToCartBtn.innerText = 'Out of stock';
                addToCartBtn.style.backgroundColor = '#ccc';
                addToCartBtn.style.cursor = 'not-allowed';

                // همه رنگ‌ها: unchecked + disabled
                colorBox.querySelectorAll('input[name="color"]').forEach(radio => {
                    radio.checked = false;
                    radio.disabled = true;
                    radio.closest('label')?.classList.add('disabled');
                });

                // سایزها هم مخفی شوند
                if (!hasSize) sizeWrapper.style.display = 'none';

            }

        });

        /* =========================
          رنگ موجود است؟
        ========================= */
        function isColorAvailable(colorId) {
            return variants.some(v =>
                v.color_id === colorId &&
                v.stock > 0
            );
        }
    </script>
@endsection
