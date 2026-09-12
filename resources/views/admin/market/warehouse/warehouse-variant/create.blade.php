@extends('admin.layouts.master2')

@section('head-tag')
    <title>Add new stock</title>
    <style>
        /* ۱. کانتینر اصلی */
        .select2-container {
            width: 100% !important;
            display: block;
        }

        /* ۲. باکس اصلی اینپوت - کاملاً هم‌قد و هم‌استایل با فیلدهای Warehouse و Quantity */
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            background-color: #ffffff !important;
            position: relative !important;
            display: flex !important;
            align-items: center !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* ۳. افکت فوکوس نرم (دقیقاً مثل بوت‌استرپ) */
        .select2-container--default.select2-container--open .select2-selection--single,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #80bdff !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
            outline: 0 !important;
        }

        /* ۴. متن داخل باکس */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057 !important;
            font-size: 14px !important;
            padding-left: 12px !important;
            padding-right: 50px !important;
            /* فضا برای ضربدر و آیکون فلش در سمت راست */
            line-height: normal !important;
            width: 100%;
        }

        /* ۵. انتقال دکمه ضربدر (Clear) به سمت راست و ترازبندی شیک */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            position: absolute !important;
            right: 28px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 16px !important;
            color: #adb5bd !important;
            cursor: pointer;
            margin: 0 !important;
            line-height: 1;
            font-weight: bold;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #dc3545 !important;
            /* قرمز شدن ضربدر هنگام هاور */
        }

        /* ۶. آیکون فلش دراپ‌داون سمت راست */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            position: absolute !important;
            right: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            height: auto !important;
            width: 20px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6c757d transparent transparent transparent !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #6c757d transparent !important;
        }

        /* ۷. پنجره بازشونده (Dropdown Menu) */
        .select2-dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
            z-index: 1051 !important;
        }

        /* ۸. آیتم‌ها و اینپوت جستجو داخل دراپ‌داون */
        .select2-results__option {
            padding: 8px 12px !important;
            font-size: 13.5px !important;
            color: #333333 !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff !important;
            color: #ffffff !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
            padding: 6px 10px !important;
            outline: none !important;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a>
                </li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Warehouse</a></li>
                <li class="breadcrumb-item active">Add Stock</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Add Stock</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.warehouse.variant.index', $warehouse) }}"
                    class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.warehouse.variant.store', $warehouse) }}" method="post">
                    @csrf
                    <section class="col-12 mt-3">
                        <div class="form-group">
                            <label>Warehouse</label>
                            <p
                                style="background-color: white; color: #333;  border: 1px solid #c7d2fe; border-radius: 6px; padding: 6px 12px; font-size: 14px;">
                                {{ $warehouse->name }}
                            </p>
                        </div>
                    </section>

                    {{-- Select Variant --}}
                    <section class="col-12 my-3">
                        <div class="form-group">
                            <label for="product_variant_id">Product Variant</label>
                            <select name="product_variant_id" id="product_variant_id" class="form-control">
                                <option></option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}"
                                        {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->product->name }} - {{ $variant->color?->name }} -
                                        {{ $variant->size?->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('product_variant_id')
                            <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </section>

                    <section class="col-12 my-3">
                        <div class="form-group">
                            <label for="stock">Quantity</label>
                            <input type="text" class="form-control form-control-sm" name="stock" id="stock"
                                value="{{ old('stock') }}">
                        </div>
                        @error('stock')
                            <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </section>

                    <section class="col-12 my-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </section>

            </section>
            </form>
        </section>
    </section>
@endsection
@section('script')
    <script>
        var product_variant_id = $('#product_variant_id');
        product_variant_id.select2({
            placeholder: 'Please select variant',
            allowClear: true,
            width: '100%'
        })
    </script>
@endsection
