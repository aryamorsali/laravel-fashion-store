@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Email notification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Notitfication</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Email notification</a></li>
                <li class="breadcrumb-item active">Edit Email notification</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Email notification</h3>
            </section>
            @include('admin.alerts.alert-section.error')

            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.notification.email.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.notification.email.update', $email) }}" method="post" id="form">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control form-control-sm" name="subject" id="subject"
                                    placeholder="optional" value="{{ old('subject' , $email->subject) }}">
                            </div>
                            @error('subject')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>



                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="published_at">Publication date</label>
                                <input type="text" name="published_at" id="published_at"
                                    class="form-control form-control-sm" value="{{ old('published_at' , $email->published_at) }}"
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
                                <textarea name="body" class="form-control form-control-sm" id="body">{{ old('body' , $email->body) }}</textarea>
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
        <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
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

        {{-- ckEditor --}}
        <script>
            ClassicEditor
                .create(document.querySelector('#body'))
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endsection
