@extends('customer.layouts.app')

@section('head-tag')
    <title>Shoping Cart</title>

    <style>
        .total-price {
            font-size: 16px;
            font-weight: 600;
            color: #222;
        }

        .delete-box {
            position: absolute;
            right: 5px;
            bottom: 5px;

            width: 26px;
            height: 26px;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
        }

        .delete-box a {
            color: #b9b9b9;
            font-size: 18px;
            line-height: 0;
            transition: 0.2s;
        }

        .delete-box a:hover {
            color: #e60023;
            transform: scale(1.15);
        }


        /* ///////////////////////////////////////////////////////////// */

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

        /* ///////////////////////////////////////////////////////////// */


        /* ROW */
        .table_row {
            position: relative;
        }

        /* IMAGE BOX */
        .cart-img-box {
            width: 100px;
            height: 125px;
            border-radius: 4px;
            overflow: hidden;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .cart-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* PRODUCT INFO */
        .product-info {
            font-size: 14px;
            color: #444;
        }

        .product-info strong {
            display: block;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .product-attr {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
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

        /* TOTAL-PRICE */
        .total-price {
            font-size: 16px;
            font-weight: 600;
            color: #222;
        }

        /* DELETE */
        .delete-box {
            position: absolute;
            right: 5px;
            bottom: 5px;
            width: 24px;
            height: 24px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .delete-box a {
            color: #c0c0c0;
            font-size: 18px;
            line-height: 0;
            transition: 0.2s;
        }

        .delete-box a:hover {
            color: #e60023;
            transform: scale(1.2);
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

            <span class="stext-109 cl4">
                Shoping Cart
            </span>
        </div>
    </div>


    <!-- Shoping Cart -->
    <form class="bg0 p-t-75 p-b-85">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                    <div class="m-l-25 m-r--38 m-lr-0-xl">
                        <div class="wrap-table-shopping-cart">
                            <table class="table-shopping-cart">
                                <tr class="table_head">
                                    <th class="column-1">Product</th>
                                    <th class="column-2"></th>
                                    <th class="column-3">Price</th>
                                    <th class="column-4">Quantity</th>
                                    <th class="column-5">Total</th>
                                </tr>

                                @php
                                    $totalCartPrice = 0;
                                    $productDiscounts = 0;
                                    $productPrices = 0;
                                @endphp

                                @if ($cartItems->count() >= 1)
                                    @foreach ($cartItems as $item)
                                        @php
                                            $price = $item->productVariant?->price;
                                            $finalPrice = $price;
                                            $discount = null;

                                            $activeAmazingSale =
                                                $item->productVariant &&
                                                $item->productVariant->amazingSale &&
                                                $item->productVariant->amazingSale->is_active &&
                                                $item->productVariant->amazingSale->start_date <= now() &&
                                                $item->productVariant->amazingSale->end_date >= now();

                                            if ($activeAmazingSale) {
                                                $discount = $item->productVariant->amazingSale->percentage;
                                                $finalPrice = $price - ($price * $discount) / 100;
                                            }
                                            //  قیمت کل نهایی با حسب تخفیف و تعداد
                                            $totalCartPrice += $item->quantity * $finalPrice;

                                            // قیمت نهایی تک آیتم
                                            $totalItemPrice = $item->quantity * $finalPrice;

                                            // مقدار تخفیف کالاها
                                            if ($activeAmazingSale) {
                                                $productDiscounts += ($price * $item->quantity * $discount) / 100;
                                            }

                                            // قیمت کل محصولات بدون تخفیف
                                            $productPrices += $price * $item->quantity;
                                        @endphp

                                        <tr class="table_row">

                                            <!-- IMAGE -->
                                            <td class="column-1">
                                                <div class="cart-img-box">
                                                    <img
                                                        src="{{ asset($item->productVariant->product->image['indexArray']['main']) }}">
                                                </div>
                                            </td>

                                            <!-- PRODUCT INFO -->
                                            <td class="column-2 product-info">
                                                <strong>{{ $item->productVariant?->product->name }}</strong>

                                                <div class="product-attr">
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
                                            </td>

                                            <!-- PRICE -->
                                            <td class="column-3" style="vertical-align: middle;">
                                                @if ($activeAmazingSale)
                                                    <div class="price-discount">{{ $discount }}% OFF</div>
                                                    <span class="price-old">${{ number_format($price, 2) }}</span>
                                                    <span class="price-box">${{ number_format($finalPrice, 2) }}</span>
                                                @else
                                                    <span class="price-box">${{ number_format($price, 2) }}</span>
                                                @endif
                                            </td>

                                            <!-- QUANTITY -->
                                            <td class="column-4" style="vertical-align: middle;">
                                                <div class="d-flex align-items-center border overflow-hidden qty-wrapper"
                                                    style="width: max-content; border-radius: 2px;">

                                                    <button type="button" class="btn-qty minus">−</button>

                                                    <input type="number" value="{{ $item->quantity }}" min="1"
                                                        data-id="{{ $item->id }}"
                                                        class="qty-input mtext-104 cl3 txt-center">

                                                    <button type="button" class="btn-qty plus">+</button>
                                                </div>
                                            </td>

                                            <!-- TOTAL-ITEM + DELETE -->
                                            <td class="column-5"
                                                style="position: relative; vertical-align: middle; padding-right: 45px;">
                                                <span class="total-price">${{ number_format($totalItemPrice, 2) }}</span>

                                                <div class="delete-box">
                                                    <a
                                                        href="{{ route('customer.sales-process.remove-from-cart', $item) }}">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif


                            </table>


                        </div>

                        <div class="flex-w flex-sb-m bor15 p-t-18 p-b-15 p-lr-40 p-lr-15-sm">
                            @if ($cartItems->count() >= 1)
                                <div class="flex-w flex-m m-r-20 m-tb-5">
                                    <input class="stext-104 cl2 plh4 size-117 bor13 p-lr-20 m-r-10 m-tb-5" type="text"
                                        name="coupon" id="coupon" placeholder="Coupon Code">

                                    <button type="button" id="couponButton">
                                        <div
                                            class="flex-c-m stext-101 cl2 size-118 bg8 bor13 hov-btn3 p-lr-15 trans-04 pointer m-tb-5">
                                            Apply coupon
                                        </div>
                                    </button>
                                    <p class="text-danger pl-3" id="errorMessage" style="font-size:14px"></p>
                                </div>
                            @else
                                <div class="flex-w flex-m m-r-20 m-tb-5">
                                    <p class="text-danger">no products were found in your shoping cart.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
                    <div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
                        <h4 class="mtext-109 cl2 p-b-30">
                            Cart Totals
                        </h4>

                        <div class="flex-w flex-t p-b-13">
                            <div class="size-209">
                                <span class="stext-110 cl2">
                                    Product prices({{ $cartItems->count() }}):
                                </span>
                            </div>

                            <div class="size-208">
                                <span class="mtext-110 cl2" id="productPrices">
                                    ${{ rtrim(rtrim(number_format($productPrices, 2), '0'), '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-w flex-t bor12 p-b-13">
                            <div class="size-209">
                                <span class="stext-110 cl2">
                                    Product discounts:
                                </span>
                            </div>

                            <div class="size-208">
                                <span class="mtext-110 cl2 text-danger" id="productDiscounts">
                                    ${{ rtrim(rtrim(number_format($productDiscounts, 2), '0'), '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-w flex-t p-b-13 pt-3" id="couponDiscountBox" hidden>
                            <div class="size-212">
                                <span class="stext-110 cl2">
                                    Coupon discount amount:
                                </span>
                            </div>

                            <div class="size-201">
                                <span class="mtext-110 cl2 text-danger" id="couponDiscountValue">
                                </span>
                            </div>
                        </div>

                        <div class="flex-w flex-t p-t-27 p-b-33">
                            <div class="size-208">
                                <span class="mtext-101 cl2">
                                    Total:
                                </span>
                            </div>

                            <div class="size-209 p-t-1">
                                <span class="mtext-110 cl2" id="totalCartPrice">
                                    ${{ rtrim(rtrim(number_format($totalCartPrice, 2), '0'), '.') }}
                                </span>
                            </div>
                        </div>

                        <button class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <!--===============================================================================================-->
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
    <script src="{{ asset('customer-assets/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
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

            let input = wrapper.querySelector('.qty-input')
            let btnPlus = wrapper.querySelector('.btn-qty.plus')
            let btnMinus = wrapper.querySelector('.btn-qty.minus')

            // تغییر دستی مقدار
            input.addEventListener('input', function() {
                if (input.value < 1) input.value = 1
                updateCartQuantity(input)
            })

            // دکمه +
            btnPlus.addEventListener('click', function() {
                let current = parseInt(input.value || 1);
                let next = current + 1;
                if (next > 10) next = 10; // محدودیت کاربر
                input.value = next;
                // اجرا کن این رویدادی که خودم ساختم روی این عنصر
                input.dispatchEvent(new Event('input'))
            })

            // دکمه -
            btnMinus.addEventListener('click', function() {
                let val = parseInt(input.value || 1) - 1
                input.value = val < 1 ? 1 : val
                input.dispatchEvent(new Event('input'))
            })

        })

        // =============================
        // درخواست AJAX
        // =============================
        function updateCartQuantity(input) {

            let quantity = input.value
            let cartItemId = input.dataset.id
            let row = input.closest('.table_row')

            fetch("{{ route('customer.sales-process.update-shoping-cart') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        cart_item_id: cartItemId,
                        quantity: quantity
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.status === "stock_error") {
                        alert("Only " + data.available + " left in stock")
                        input.value = data.available
                        return
                    }

                    // total item
                    row.querySelector('.total-price').innerText = "$" + data.totalItemPrice

                    // cart summary
                    document.querySelector('#totalCartPrice').innerText = "$" + data.totalCartPrice
                    document.querySelector('#productPrices').innerText = "$" + data.productPrices
                    document.querySelector('#productDiscounts').innerText = "$" + data.productDiscounts
                })
        }
    </script>

    <!--===============================================================================================-->

    <script>
        document.querySelector('#couponButton').addEventListener('click', function() {

            let input = document.querySelector('#coupon');
            let errorMessage = document.querySelector('#errorMessage');

            // =============================
            // درخواست AJAX
            // =============================

            fetch("{{ route('customer.sales-process.coupon') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        coupon: input.value,
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.status === "error") {
                        errorMessage.innerText = data.message;
                        return
                    }

                    if (data.status === 'success') {
                        document.querySelector('#couponDiscountBox').hidden = false;
                        document.querySelector('#couponDiscountValue').innerText = "$" + data.couponDiscountAmount;
                        document.querySelector('#totalCartPrice').innerText = "$" + data.finalPrice;
                    } else {
                        errorMessage.innerText = data.message;
                    }
                })
        })
    </script>
@endsection
