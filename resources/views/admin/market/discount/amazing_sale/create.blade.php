@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Amazing Sales</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Amazing Sales</a></li>
                <li class="breadcrumb-item active">Create Amazing Sales</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Amazing Sales</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.discount.amazingSale') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.discount.amazingSale.store') }}" method="post">
                    @csrf
                    <section class="row">

                        <section class="col-12 col-md-6 my-3">
                            {{-- انتخاب محصول --}}
                            <div class="form-group">
                                <label for="product_id">Product selection</label>
                                <select id="product_id" class="form-control form-control-sm">
                                    <option value="">Select the product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- واریانت‌ها --}}
                            <div id="variants-container">
                                @foreach ($products as $product)
                                    <div class="card variants-wrapper d-none shadow-sm mt-3"
                                        data-product-id="{{ $product->id }}">
                                        <div class="card-body">
                                            @if ($product->variants->count() > 1)
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input select-all"
                                                        id="select-all-{{ $product->id }}">
                                                    <label class="form-check-label fw-semibold"
                                                        for="select-all-{{ $product->id }}">
                                                        Apply to all variants
                                                    </label>
                                                </div>
                                            @endif

                                            @foreach ($product->variants as $variant)
                                                <div class="form-check mb-1">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="product_variant_ids[]" id="variant-{{ $variant->id }}"
                                                        value="{{ $variant->id }}">
                                                    <label class="form-check-label" for="variant-{{ $variant->id }}">
                                                        {{ $product->name }}
                                                        @if ($variant->color || $variant->size)
                                                            - {{ $variant->color?->name }}
                                                            @if ($variant->size)
                                                                / {{ $variant->size->name }}
                                                            @else
                                                                <span class="text-danger">/ no size</span>
                                                            @endif
                                                        @endif
                                                        <span
                                                            class="text-muted small">(${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }})</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach


                            </div>

                            @error('product_variant_ids')
                                <div class="text-danger mt-2" style="font-size: 12px">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror

                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="percentage">Discount percentage</label>
                                <input type="text" class="form-control form-control-sm" name="percentage" id="percentage"
                                    value="{{ old('percentage') }}">
                            </div>
                            @error('percentage')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="start_date">Start date</label>
                                <input type="text" name="start_date" id="start_date" class="form-control form-control-sm"
                                    value="{{ old('start_date') }}" placeholder="Select date">
                                @error('start_date')
                                    <div class="text-danger mt-2" style="font-size: 12px;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="end_date">End date</label>
                                <input type="text" name="end_date" id="end_date" class="form-control form-control-sm"
                                    value="{{ old('end_date') }}" placeholder="Select date">
                                @error('end_date')
                                    <div class="text-danger mt-2" style="font-size: 12px;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="is_active">Status</label>
                                <select name="is_active" class="form-control form-control-sm" id="is_active">
                                    <option value="0" @if (old('is_active') == 0) selected @endif>
                                        inactive
                                    </option>
                                    <option value="1" @if (old('is_active') == 1 || old('is_active') === null) selected @endif>active
                                    </option>
                                </select>
                            </div>
                            @error('is_active')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>



                        <section class="col-12 my-3 d-flex justify-content-end">
                            <button class="btn btn-primary">Submit</button>
                        </section>
                    </section>
                </form>
            </section>
        </section>
    @endsection
    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        {{-- published_date --}}
        <script>
            flatpickr("#start_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "F j, Y H:i",
            });

            flatpickr("#end_date", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                altInput: true,
                altFormat: "F j, Y H:i",
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                const productSelect = document.getElementById('product_id');
                const wrappers = document.querySelectorAll('.variants-wrapper');

                productSelect.addEventListener('change', () => {
                    // همه رو پنهان کن
                    wrappers.forEach(wrapper => {
                        wrapper.classList.add('d-none');
                        // غیرفعال‌کردن تیک‌ها
                        wrapper.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked =
                            false);
                    });

                    const productId = productSelect.value;
                    if (!productId) return;

                    // نمایش گروه مربوط به محصول انتخاب‌شده
                    const active = document.querySelector(`.variants-wrapper[data-product-id="${productId}"]`);
                    if (active) active.classList.remove('d-none');
                });

                // "انتخاب همه" برای هر محصول
                document.querySelectorAll('.variants-wrapper').forEach(wrapper => {
                    const selectAll = wrapper.querySelector('.select-all');
                    if (!selectAll) return; // بعضی محصولات یه واریانت دارن

                    selectAll.addEventListener('change', () => {
                        const allBoxes = wrapper.querySelectorAll(
                            'input[name="product_variant_ids[]"]');
                        allBoxes.forEach(cb => cb.checked = selectAll.checked);
                    });
                });

            });
        </script>
    @endsection
