@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Coupon</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <style>
        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f1f5ff;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Coupons</a></li>
                <li class="breadcrumb-item active">Edit Coupon</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Coupon</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.discount.coupon') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.discount.coupon.update', $coupon) }}" method="post">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" class="form-control form-control-sm" name="code" id="code"
                                    value="{{ old('code', $coupon->code) }}">
                            </div>
                            @error('code')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="type">Coupon type</label>
                                <select name="type" class="form-control form-control-sm" id="type">
                                    <option value="0" @if (old('type', $coupon->type) == 0) selected @endif>common
                                    </option>
                                    <option value="1" @if (old('type', $coupon->type) == 1) selected @endif>private
                                    </option>
                                </select>
                            </div>
                            @error('type')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="users">users</label>
                                <select name="user_id" id="users" class="form-control form-control-sm"
                                    {{ old('type') == 0 ? 'disabled' : '' }} style="border: 1px solid #c7d2fe;">
                                    <option value="">Select a user</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            @if (old('user_id', $coupon->user_id) == $user->id) selected @endif>
                                            {{ $user->fullName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('user_id')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="amount_type">Discount type</label>
                                <select name="amount_type" class="form-control form-control-sm" id="amount_type">
                                    <option value="0" @if (old('amount_type', $coupon->amount_type) == 0) selected @endif>percentage
                                    </option>
                                    <option value="1" @if (old('amount_type', $coupon->amount_type) == 1) selected @endif>numerical
                                    </option>
                                </select>
                            </div>
                            @error('amount_type')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 col-md-6 my-2">
                            <div class="form-group">
                                <label for="amount">Discount amount</label>
                                <input type="text" name="amount" id="amount" class="form-control form-control-sm"
                                    value="{{ old('amount', $coupon->amount) }}">
                            </div>
                            @error('amount')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-2">
                            <div class="form-group">
                                <label for="discount_ceiling">Maximum discount</label>
                                <input type="text" name="discount_ceiling" id="discount_ceiling" placeholder="optional"
                                    class="form-control form-control-sm"
                                    value="{{ old('discount_ceiling', $coupon->discount_ceiling) }}">
                            </div>
                            @error('discount_ceiling')
                                <div class="text-danger" style="margin-top: -9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="start_date">Start date</label>
                                <input type="text" name="start_date" id="start_date" class="form-control form-control-sm"
                                    value="{{ old('start_date', $coupon->start_date) }}" placeholder="Select date">
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
                                <input type="text" name="end_date" id="end_date"
                                    class="form-control form-control-sm" value="{{ old('end_date', $coupon->end_date) }}"
                                    placeholder="Select date">
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
                                    <option value="0" @if (old('status', $coupon->status) == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('status', $coupon->status) == 1 && $coupon->end_date && now() <= $coupon->end_date) selected @endif>active
                                    </option>
                                    <option value="2" @if (now() > $coupon->end_date) selected @endif>expired
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
        <script>
            $(document).ready(function() {
                $("#type").change(function() {
                    if ($(this).val() == 1) {
                        $("#users").removeAttr('disabled');
                    } else {
                        $("#users").attr('disabled', 'disabled').val(''); // پاک کردن مقدار
                    }
                }).trigger('change'); // برای مقدار اولیه
            });
        </script>
        <script>
            // قفل کردن اینپوت سقف تخفیف اگر نوع عددی بود
            document.getElementById('amount_type').addEventListener('change', function() {
                let ceilingInput = document.getElementById('discount_ceiling');

                if (this.value == '1') { // Fixed
                    ceilingInput.disabled = true;
                    ceilingInput.value = '';
                } else {
                    ceilingInput.disabled = false;
                }
            });
        </script>
    @endsection
