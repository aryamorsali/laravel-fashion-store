@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Common Discounts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Common Discounts</a></li>
                <li class="breadcrumb-item active">Create Common Discounts</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Common Discounts</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.discount.common_discount') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.discount.common_discount.store') }}" method="post">
                    @csrf
                    <section class="row">
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
                                <label for="discount_ceiling">Maximum discount</label>
                                <input type="text" name="discount_ceiling" id="discount_ceiling" placeholder="optional"
                                    class="form-control form-control-sm" value="{{ old('discount_ceiling') }}">
                            </div>
                            @error('discount_ceiling')
                                <div class="text-danger" style="margin-top: -9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="minimal_order_amount">Minimum purchase amount</label>
                                <input type="text" class="form-control form-control-sm" name="minimal_order_amount"
                                    id="minimal_order_amount" placeholder="optional"
                                    value="{{ old('minimal_order_amount') }}">
                            </div>
                            @error('minimal_order_amount')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control form-control-sm" name="title" id="title" placeholder="The title of the occasion"
                                    value="{{ old('title') }}">
                            </div>
                            @error('title')
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
                                <label for="status">Status</label>
                                <select name="status" class="form-control form-control-sm" id="status">
                                    <option value="0" @if (old('status') == 0) selected @endif>
                                        inactive
                                    </option>
                                    <option value="1" @if (old('status') == 1 || old('status') === null) selected @endif>active
                                    </option>
                                </select>
                            </div>
                            @error('status')
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
    @endsection
