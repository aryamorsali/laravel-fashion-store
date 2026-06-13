@extends('customer.layouts.app')

@section('head-tag')
    <title>Shop</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/slick/slick.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/vendor/MagnificPopup/magnific-popup.css') }}">

    <style>
        .price-range input {
            font-size: 0.9rem;
            padding: 0.5rem;
            border-radius: 0.25rem;
            border: none;
            outline: none;
            background-color: rgba(0, 0, 0, 0.1);
            width: 90%;
            display: inline-block;
        }

        .price-range input:focus {
            box-shadow: 0rem 0rem 0.2rem 0.1rem rgba(0, 0, 0, 0.1);
            background-color: rgba(0, 0, 0, 0.1);
        }

        /* apply btn  */

        .filter-btn-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            width: 100%;
        }

        .apply-filter-btn {
            background-color: #e63946;
            color: #fff;
            border-radius: 7px !important;
            border: none;
            font-size: 17px !important;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
            width: 250px !important;
            padding: 18px 30px;

            font-family: Poppins-Medium;
            line-height: 1.466667;
            text-transform: uppercase;
        }

        .apply-filter-btn:hover {
            background-color: #c82333;
        }

        .delete-filter-btn {
            width: 250px;
            border-radius: 5px !important;
            font-family: Poppins-Medium;
            font-size: 14px;
            line-height: 1.466667;
            text-transform: uppercase;
            color: #fff;
        }

        .form-check-label {
            padding-left: 1px;
            color: #aaa
        }

        .form-check-label:hover {
            color: #6c7ae0;
        }

        .filter-tag-active {
            color: #6c7ae0 !important;
            border-color: #6c7ae0 !important;
        }


        .filter-multi-col ul {
            column-count: 2;
            column-gap: 0px !important;
            padding-left: 0;
        }

        .filter-multi-col li {
            break-inside: avoid;
            list-style: none;
        }
    </style>
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
            font-size: 1em;
            text-decoration: line-through;
            margin-right: 6px;
        }

        .new-price {
            color: #e53935;
            font-weight: 700;
            font-size: 1.07em;
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

    <!-- Product -->
    <div class="bg0 m-t-23 p-b-140">
        <div class="container">
            <div>
                <form class="flex-w flex-sb-m p-b-52" action="{{ url()->current() }}" method="GET">

                    @if (request('tag'))
                        <input type="hidden" name="tag" value="{{ request('tag') }}">
                    @endif

                    @if (request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif


                    <div class="flex-w flex-l-m filter-tope-group m-tb-10">
                        <a href="{{ route('customer.market.shop', request()->except('page', 'category')) }}"
                            class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 {{ request()->route('category') ? '' : 'how-active1' }}">
                            All Products
                        </a>

                        @foreach ($productCategories as $category)
                            <a href="{{ route('customer.market.shop', ['category' => $category->slug] + request()->except('page')) }}"
                                class="{{ request()->route('category')?->slug === $category->slug ? 'how-active1' : '' }}
                            stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5">
                                {{ $category->name }}
                            </a>
                        @endforeach

                    </div>

                    <div class="flex-w flex-c-m m-tb-10">
                        <div
                            class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
                            <i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
                            <i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                            Filter
                            @if ($activeFiltersCount > 0)
                                <span
                                    style="background:red; color:white; border-radius:50%; padding: 0px 4px; font-size:13px; margin-left:5px;">
                                    {{ $activeFiltersCount }}
                                </span>
                            @endif
                        </div>

                        <div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
                            <i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
                            <i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
                            Search
                        </div>
                    </div>


                    <!-- Search product -->
                    <div class="dis-none panel-search w-full p-t-10 p-b-15">
                        <div class="bor8 dis-flex p-l-15">
                            <button type="submit" class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
                                <i class="zmdi zmdi-search"></i>
                            </button>

                            <input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search"
                                value="{{ request()->search }}" placeholder="Search">
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="dis-none panel-filter w-full p-t-10">
                        <div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm row g-3">

                            <div class="filter-col1 p-b-27  col-6 col-md-4 col-lg-auto">
                                <div class="mtext-102 cl2 p-b-15">
                                    Category
                                </div>

                                <ul>
                                    @php
                                        $currentCategory = request()->route('category');

                                        if (!$currentCategory) {
                                            $categoriesToShow = $productCategories;
                                        } else {
                                            if ($currentCategory->children()->exists()) {
                                                $categoriesToShow = $currentCategory->children;
                                            } elseif ($currentCategory->parent) {
                                                $categoriesToShow = $currentCategory->parent->children;
                                            } else {
                                                $categoriesToShow = $productCategories;
                                            }
                                        }
                                    @endphp

                                    @foreach ($categoriesToShow as $category)
                                        <li class="p-b-6">
                                            <a href="{{ route('customer.market.shop', ['category' => $category->slug] + request()->except('page')) }}"
                                                class="filter-link stext-106 trans-04
                                        {{ $currentCategory?->slug === $category->slug ? 'filter-link-active' : '' }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>


                            <div class="filter-col2 p-b-27 col-6 col-md-4 col-lg-auto">
                                <div class="mtext-102 cl2">
                                    Brand
                                </div>
                                <div class="content-wrapper p-3 rounded-2 mb-3">
                                    <div class="sidebar-brand-wrapper">

                                        @foreach ($brands as $brand)
                                            <div class="form-check sidebar-brand-item">

                                                <input class="form-check-input" name="brands[]" type="checkbox"
                                                    value="{{ $brand->slug }}" id="brand{{ $brand->id }}"
                                                    @if (request()->brands && in_array($brand->slug, request()->brands)) checked @endif>

                                                <label class="form-check-label d-flex justify-content-between"
                                                    for="brand{{ $brand->id }}">
                                                    <span>{{ $brand->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                            <div class="filter-col3 p-l-30 p-b-27 col-6 col-md-4 col-lg-auto">
                                <div class="mtext-102 cl2 p-b-15">
                                    Color
                                </div>

                                <ul>
                                    @foreach ($colors as $color)
                                        <li class="p-b-6 d-flex align-items-center">

                                            <span class="fs-15 lh-12 m-r-10" style="color: {{ $color->hex_code }};">
                                                <i class="zmdi zmdi-circle"></i>
                                            </span>

                                            <input class="form-check-input m-r-10" name="colors[]" type="checkbox"
                                                value="{{ $color->slug }}" id="color{{ $color->id }}"
                                                @checked(request()->colors && in_array($color->slug, request()->colors))>

                                            <label class="form-check-label" for="color{{ $color->id }}">
                                                {{ $color->name }}
                                            </label>

                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="filter-col4 filter-multi-col p-l-30 p-b-27 p-r-20 col-6 col-md-4 col-lg-auto">
                                <div class="mtext-102 cl2 p-b-15">
                                    Size
                                </div>

                                <ul>
                                    @foreach ($sizes as $size)
                                        <li class="p-b-6">
                                            <input class="form-check-input" name="sizes[]" type="checkbox"
                                                value="{{ $size->slug }}" id="size{{ $size->id }}"
                                                @if (request()->sizes && in_array($size->slug, request()->sizes)) checked @endif>

                                            <label class="form-check-label d-flex justify-content-between"
                                                for="size{{ $size->id }}">
                                                <span>{{ $size->name }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>


                            <div class="filter-col5 p-b-27 col-6 col-md-4 col-lg-auto">
                                <div class="mtext-102 cl2 p-b-15">
                                    Price Range
                                </div>

                                <div class="price-range d-flex flex-column gap-2">

                                    <div class="p-1">
                                        <input type="text" class="form-control" placeholder="Price from ..."
                                            name="min_price" value="{{ request()->min_price }}">
                                    </div>

                                    <div class="p-1">
                                        <input type="text" class="form-control" placeholder="Price up to ..."
                                            name="max_price" value="{{ request()->max_price }}">
                                    </div>
                                    @error('max_price')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                    @error('min_price')
                                        <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror

                                </div>
                            </div>

                            <div class="filter-col6 p-l-30 p-b-27 col-6 col-md-4 col-lg-auto">
                                <div class="mtext-102 cl2 p-b-15">
                                    Stock
                                </div>
                                <ul>
                                    <li class="p-b-6">
                                        <input type="checkbox" class="form-check-input" id="inStock" name="in_stock"
                                            value="1" {{ request('in_stock') == '1' ? 'checked' : '' }}>

                                        <label class="form-check-label" for="inStock">
                                            In Stock
                                        </label>
                                    </li>

                                    <li class="p-b-6">
                                        <input type="checkbox" class="form-check-input" id="outOfStock"
                                            name="out_of_stock" value="1"
                                            {{ request('out_of_stock') == '1' ? 'checked' : '' }}>

                                        <label class="form-check-label" for="outOfStock">
                                            Out of Stock
                                        </label>
                                    </li>
                                </ul>

                                <div class="mtext-102 cl2 p-b-15 p-t-15">
                                    Discount
                                </div>

                                <ul>
                                    <li class="p-b-6">
                                        <input class="form-check-input" type="checkbox" id="onSale" name="on_sale"
                                            value="1" @checked(request('on_sale') == 1)>

                                        <label class="form-check-label" for="onSale">
                                            On Sale
                                        </label>
                                    </li>

                                    <li class="p-b-6">
                                        <input class="form-check-input" type="checkbox" id="bigDeals" name="big_deals"
                                            value="1" @checked(request('big_deals') == 1)>
                                        <label class="form-check-label" for="bigDeals">Big Deals (30%+)</label>
                                    </li>
                                </ul>
                            </div>

                            <div class="filter-col8 p-l-40 p-b-27  col-6 col-lg-auto">
                                <div class="mtext-102 cl2 p-b-15">
                                    Tags
                                </div>

                                <div class="flex-w p-t-4 m-r--5">
                                    @foreach ($tags as $tag)
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'tag') + ['tag' => $tag->slug]) }}"
                                            class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5 {{ request('tag') == $tag->slug ? 'filter-tag-active' : '' }}">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="filter-col9 p-l-80 p-b-27 col-6 col-lg-auto">
                                <div class="mtext-102 cl2 p-b-15">
                                    Sort By
                                </div>

                                <ul>
                                    <li class="p-b-6">
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'sort')) }}"
                                            class="filter-link stext-106 trans-04 {{ request()->has('sort') ? '' : 'filter-link-active' }}">
                                            Default
                                        </a>
                                    </li>

                                    <li class="p-b-6">
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'sort') + ['sort' => 'trending']) }}"
                                            class="filter-link stext-106 trans-04 {{ request('sort') == 'trending' ? 'filter-link-active' : '' }}">
                                            Trending
                                        </a>
                                    </li>

                                    <li class="p-b-6">
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'sort') + ['sort' => 'best_selling']) }}"
                                            class="filter-link stext-106 trans-04 {{ request('sort') == 'best_selling' ? 'filter-link-active' : '' }}">
                                            Best Selling
                                        </a>
                                    </li>

                                    <li class="p-b-6">
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'sort') + ['sort' => 'top_rated']) }}"
                                            class="filter-link stext-106 trans-04 {{ request('sort') == 'top_rated' ? 'filter-link-active' : '' }}">
                                            Top Rated
                                        </a>
                                    </li>

                                    <li class="p-b-6">
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'sort') + ['sort' => 'newness']) }}"
                                            class="filter-link stext-106 trans-04 {{ request('sort') == 'newness' ? 'filter-link-active' : '' }}">
                                            Newness
                                        </a>
                                    </li>

                                    <li class="p-b-6">
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'sort') + ['sort' => 'cheapest']) }}"
                                            class="filter-link stext-106 trans-04 {{ request('sort') == 'cheapest' ? 'filter-link-active' : '' }}">
                                            Cheapest
                                        </a>
                                    </li>

                                    <li class="p-b-6">
                                        <a href="{{ route('customer.market.shop', ['category' => $currentCategory?->slug] + request()->except('page', 'sort') + ['sort' => 'most_expensive']) }}"
                                            class="filter-link stext-106 trans-04 {{ request('sort') == 'most_expensive' ? 'filter-link-active' : '' }}">
                                            Most expensive
                                        </a>
                                    </li>
                                </ul>
                            </div>


                            <div
                                class="filter-col10 p-r-20 d-flex flex-column justify-content-start align-items-end p-b-27 col-12 col-lg-auto">
                                <!-- Buttons -->
                                <div class="filter-btn-wrapper pt-4">

                                    <button class="apply-filter-btn cl0 bor1" type="submit">
                                        Apply filter
                                    </button>

                                    <a href="{{ route('customer.market.shop') }}"
                                        class="btn btn-primary delete-filter-btn">
                                        Delete filters
                                    </a>

                                </div>

                            </div>

                        </div>
                    </div>

                </form>
            </div>

            <div class="row isotope-grid">

                @foreach ($products as $product)
                    <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item women">
                        <!-- Block2 -->
                        <div class="block2">
                            <div class="block2-pic hov-img0">

                                @php
                                    // -----------------------------
                                    // 1) دریافت فیلترهای کاربر
                                    // -----------------------------
                                    $fColors = request()->colors ?? [];
                                    $fSizes = request()->sizes ?? [];
                                    $fMinPrice = request()->min_price;
                                    $fMaxPrice = request()->max_price;
                                    $fOnSale = request()->on_sale == 1;
                                    $fBigDeals = request()->big_deals == 1;
                                    $fInStock = request()->in_stock == 1;
                                    $fOutOfStock = request()->out_of_stock == 1;
                                    $sortBy = request()->sort;

                                    // آیا کاربر هیچ فیلتری زده؟
                                    $isAnyFilterActive =
                                        !empty($fColors) ||
                                        !empty($fSizes) ||
                                        $fMinPrice ||
                                        $fMaxPrice ||
                                        $fOnSale ||
                                        $fBigDeals ||
                                        $fInStock ||
                                        $fOutOfStock;

                                    // ------------------------------------------------------
                                    // 2) فیلتر کردن واریانت‌ها بر اساس فیلترهای کاربر
                                    // ------------------------------------------------------
                                    $filteredVariants = $product->variants->filter(function ($v) use (
                                        $fColors,
                                        $fSizes,
                                        $fMinPrice,
                                        $fMaxPrice,
                                        $fOnSale,
                                        $fBigDeals,
                                        $fInStock,
                                        $fOutOfStock,
                                    ) {
                                        // فیلتر رنگ
                                        if ($fColors && !in_array(optional($v->color)->slug, $fColors)) {
                                            return false;
                                        }

                                        // فیلتر سایز
                                        if ($fSizes && !in_array(optional($v->size)->slug, $fSizes)) {
                                            return false;
                                        }

                                        // فیلتر قیمت
                                        if ($fMinPrice && $v->final_price < $fMinPrice) {
                                            return false;
                                        }
                                        if ($fMaxPrice && $v->final_price > $fMaxPrice) {
                                            return false;
                                        }

                                        // اگر کاربر یکی از این دوتا رو زده بود
                                        if ($fInStock || $fOutOfStock) {
                                            // فیلتر موجودی
                                            if ($fInStock && !$fOutOfStock && $v->availableStock() <= 0) {
                                                return false;
                                            }

                                            // فیلتر ناموجودی
                                            if ($fOutOfStock && !$fInStock && $v->availableStock() > 0) {
                                                return false;
                                            }
                                        }

                                        // فیلتر تخفیف
                                        if ($fOnSale || $fBigDeals) {
                                            // اگر تخفیف فعال ندارد، رد کن
                                            if (!$v->activeAmazingSale) {
                                                return false;
                                            }

                                            // اگر فقط Big Deals زده شده بود (نه هر دو!) → 30٪+
                                            if ($fBigDeals && !$fOnSale && $v->activeAmazingSale->percentage < 30) {
                                                return false;
                                            }
                                        }

                                        return true;
                                    });

                                    // --------------------------------------------
                                    //  انتخاب variant مناسب بر اساس شرایط
                                    // --------------------------------------------
                                    $variant = null;

                                    $basePool = $isAnyFilterActive ? $filteredVariants : $product->variants;

                                    if ($basePool->isNotEmpty()) {
                                        // اولویت با موجودهاست، مگر اینکه کاربر فقط out_of_stock زده باشد
                                        $inStockPool = $basePool->filter(fn($v) => $v->availableStock() > 0);

                                        $pool = $inStockPool->isNotEmpty() ? $inStockPool : $basePool;

                                        $variant = $pool->sortBy('final_price')->first();
                                    }

                                    $isVariantAvailable = $variant?->availableStock() > 0;

                                    $isProductAvailable = $product->variants->sum(fn($v) => $v->availableStock()) > 0;

                                @endphp


                                @if ($variant?->has_amazing_sale)
                                    <span class="badge-amazing">
                                        Up to {{ $variant->discount_percentage }}% off
                                    </span>

                                    <span class="amazing-timer" data-end="{{ $variant->amazingSale->end_date }}">
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


                                    <span class="stext-105 cl3">

                                        @if (!$isProductAvailable)
                                            @if ($variant?->has_amazing_sale)
                                                <del class="old-price">
                                                    ${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }}
                                                </del>

                                                <span class="new-price">
                                                    ${{ rtrim(rtrim(number_format($variant->final_price, 2), '0'), '.') }}
                                                </span></br>
                                            @else
                                                ${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }}</br>
                                            @endif

                                            <p class="text-danger">Out of stock</p>
                                        @elseif (!$isVariantAvailable)
                                            @if ($variant?->has_amazing_sale)
                                                <del class="old-price">
                                                    ${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }}
                                                </del>

                                                <span class="new-price">
                                                    ${{ rtrim(rtrim(number_format($variant->final_price, 2), '0'), '.') }}
                                                </span></br>
                                            @else
                                                ${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }}</br>
                                            @endif

                                            <span class="text-danger">
                                                This item is not available. Check the available options.
                                            </span>
                                        @else
                                            @if ($variant?->has_amazing_sale)
                                                <del class="old-price">
                                                    ${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }}
                                                </del>

                                                <span class="new-price">
                                                    ${{ rtrim(rtrim(number_format($variant->final_price, 2), '0'), '.') }}
                                                </span>
                                            @else
                                                ${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }}
                                            @endif
                                        @endif


                                    </span>


                                </div>

                                <div class="block2-txt-child2 flex-r p-t-3">
                                    <a href="#" class="btn-addwish-b2 dis-block pos-relative js-addwish-b2">
                                        <img class="icon-heart1 dis-block trans-04"
                                            src="{{ asset('images/icons/icon-heart-01.png') }}" alt="ICON">
                                        <img class="icon-heart2 dis-block trans-04 ab-t-l"
                                            src="{{ asset('images/images/icons/icon-heart-02.png') }}" alt="ICON">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Load more -->
            <div class="flex-c-m flex-w w-full p-t-45">
                @if ($products->hasMorePages())
                    <button id="load-more-btn" data-next-page="{{ $products->currentPage() + 1 }}"
                        class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
                        Load More
                    </button>
                @endif
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('customer-assets/vendor/select2/select2.min.js') }}"></script>

    <script>
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
    <script src="js/slick-custom.js') }}"></script>
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

    <script>
        //     <!-- Load more 
        //     -->
        // <div class="flex-c-m flex-w w-full p-t-45">
        //     @if ($products->hasMorePages())
        //     <button id="load-more-btn" data-next-page="{{ $products->currentPage() + 1 }}"
        //         class="flex-c-m stext-101 cl5 size-103 bg2 bor1 hov-btn1 p-lr-15 trans-04">
        //         Load More
        //     </button>
        //     @endif
        // </div>

        // Load More Products

        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('load-more-btn');

            btn?.addEventListener('click', function() {
                const nextPage = btn.dataset.nextPage;
                const params = new URLSearchParams(window.location.search);
                params.set('page', nextPage);

                btn.textContent = 'Loading...';

                fetch(`/shop?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.text())
                    .then(html => {
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const newItems = doc.querySelectorAll('.isotope-item');
                        const container = document.querySelector('.isotope-grid');

                        newItems.forEach(item => container.appendChild(item));

                        // صبر کن تصاویر لود بشن بعد layout بزن
                        const images = container.querySelectorAll('img');
                        let loadedCount = 0;
                        const totalImages = images.length;

                        function onImageLoad() {
                            loadedCount++;
                            if (loadedCount >= totalImages) {
                                if (typeof $.fn.isotope !== 'undefined') {
                                    $(container).isotope('appended', $(newItems));
                                    $(container).isotope('layout');
                                }
                            }
                        }

                        if (totalImages === 0) {
                            $(container).isotope('appended', $(newItems));
                            $(container).isotope('layout');
                        } else {
                            images.forEach(img => {
                                if (img.complete) {
                                    onImageLoad();
                                } else {
                                    img.addEventListener('load', onImageLoad);
                                    img.addEventListener('error', onImageLoad);
                                }
                            });
                        }

                        btn.dataset.nextPage = parseInt(nextPage) + 1;

                        if (!doc.getElementById('load-more-btn')) {
                            btn.remove();
                        } else {
                            btn.textContent = 'Load More';
                        }
                    });
            });
        });
    </script>
@endsection
