@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create SMS notification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Notitfication</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">SMS notification</a></li>
                <li class="breadcrumb-item active">Create SMS notification</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create SMS notification</h3>
            </section>
            @include('admin.alerts.alert-section.error')

            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.notification.sms.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.notification.sms.store') }}" method="post" id="form">
                    @csrf
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control form-control-sm" name="title" id="title" placeholder="optional"
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
                                <label for="published_at">Publication date</label>
                                <input type="text" name="published_at" id="published_at"
                                    class="form-control form-control-sm" value="{{ old('published_at') }}"
                                    placeholder="Select date (optional)">
                                @error('published_at')
                                    <div class="text-danger mt-2" style="font-size: 12px;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea name="body" class="form-control form-control-sm" id="body" rows="6">{{ old('body') }}</textarea>
                            </div>
                            @error('body')
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
            flatpickr("#published_at", {
                enableTime: true,
                dateFormat: "Y-m-d H:i", // مقدار واقعی‌ای که به سرور میره
                altInput: true, // یک فیلد فیک می‌سازه فقط برای نمایش به کاربر
                altFormat: "F j, Y H:i", // فرمت نمایش به کاربر
            });
        </script>

       
    @endsection
