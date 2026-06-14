@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Amazing Sales</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Amazing Sales</a></li>
                <li class="breadcrumb-item active">Edit Amazing Sales</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Amazing Sales</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.discount.amazingSale') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.discount.amazingSale.update', $amazingSale) }}" method="post">
                    @csrf
                    @method('put')
                    <section class="row">

                        <div class="form-group">
                            <label>Product Variant</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $amazingSale->productVariant->product->name }} - {{ $amazingSale->productVariant->color?->name }} / {{ $amazingSale->productVariant->size?->name }}">
                        </div>



                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="percentage">Discount percentage</label>
                                <input type="text" class="form-control form-control-sm" name="percentage" id="percentage"
                                    value="{{ old('percentage', $amazingSale->percentage) }}">
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
                                    value="{{ old('start_date', $amazingSale->start_date) }}" placeholder="Select date">
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
                                    value="{{ old('end_date', $amazingSale->end_date) }}" placeholder="Select date">
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
                                    <option value="0" @if (old('is_active', $amazingSale->is_active) == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('is_active', $amazingSale->is_active) == 1) selected @endif>active</option>
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
            const productSelect = document.getElementById('product_id');
            const wrappers = document.querySelectorAll('.variants-wrapper');

            function toggleVariants(productId) {
                wrappers.forEach(w => {
                    w.classList.toggle(
                        'd-none',
                        w.dataset.productId !== productId
                    );
                });
            }

            // on load (edit / old)
            if (productSelect.value) {
                toggleVariants(productSelect.value);
            }

            // on change
            productSelect.addEventListener('change', e => {
                toggleVariants(e.target.value);
            });

            // select all
            document.querySelectorAll('.select-all').forEach(selectAll => {
                selectAll.addEventListener('change', function() {
                    const card = this.closest('.variants-wrapper');
                    card.querySelectorAll(
                        'input[name="product_variant_ids[]"]'
                    ).forEach(cb => cb.checked = this.checked);
                });
            });
        </script>
    @endsection
