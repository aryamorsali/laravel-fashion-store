@extends('customer.layouts.app')

@section('head-tag')
    <title>Create Ticket</title>

    <style>
        .profile-sidebar-menu {
            padding-top: 12px;
        }

        .profile-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 5px;
            border: 0;
            border-bottom: 1px solid #f0f0f0;
            background: transparent;
            color: #555;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
            transition: all .2s ease;
        }

        .profile-menu-item:hover,
        .profile-menu-item.active {
            color: #717fe0;
            padding-left: 10px;
        }

        .profile-menu-item.active {
            font-weight: 600;
        }

        .profile-logout:hover {
            color: #d9534f;
        }

        /////////////////////////////////////////////////////////////////////////////////



        .ticket-create-wrapper {
            border: 1px solid #e6e6e6;
            background-color: #fff;
            padding: 35px 40px;
        }

        .ticket-create-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            padding-bottom: 25px;
            margin-bottom: 30px;
            border-bottom: 1px solid #e6e6e6;
        }

        .ticket-form-label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-size: 14px;
            font-weight: 600;
        }

        .ticket-form-label span {
            color: #e65540;
        }

        .ticket-form-label small {
            margin-left: 4px;
            color: #888;
            font-size: 11px;
            font-weight: 400;
        }

        .ticket-input-wrap {
            width: 100%;
            border: 1px solid #e6e6e6;
            transition: border-color .3s;
        }

        .ticket-input-wrap:focus-within {
            border-color: #717fe0;
        }

        .ticket-form-input,
        .ticket-form-select,
        .ticket-form-textarea {
            width: 100%;
            border: 0;
            outline: 0;
            color: #333;
            font-size: 13px;
            background: transparent;
        }

        .ticket-form-input {
            height: 46px;
            padding: 0 15px;
        }

        .ticket-select-wrap {
            position: relative;
            border: 1px solid #e6e6e6;
            transition: border-color .3s;
        }

        .ticket-select-wrap::after {
            content: "\f2f9";
            position: absolute;
            top: 50%;
            right: 15px;
            color: #777;
            font-family: "Material-Design-Iconic-Font";
            font-size: 17px;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .ticket-select-wrap:focus-within {
            border-color: #717fe0;
        }

        .ticket-form-select {
            height: 46px;
            padding: 0 15px;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
        }

        .ticket-form-textarea {
            min-height: 170px;
            padding: 15px;
            border: 1px solid #e6e6e6;
            line-height: 1.9;
            resize: vertical;
            transition: border-color .3s;
        }

        .ticket-form-textarea:focus {
            border-color: #717fe0;
        }

        .ticket-form-input::placeholder,
        .ticket-form-textarea::placeholder {
            color: #aaa;
        }

        .ticket-error-text {
            display: block;
            margin-top: 7px;
            color: #e65540;
            font-size: 11px;
        }

        /* File upload */
        .ticket-file-upload {
            position: relative;
            display: flex;
            align-items: center;
            min-height: 72px;
            padding: 13px 17px;
            border: 1px dashed #c8c8c8;
            background-color: #fafafa;
            cursor: pointer;
            transition: all .3s;
        }

        .ticket-file-upload:hover {
            border-color: #717fe0;
            background-color: #f8f8ff;
        }

        .ticket-file-upload-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            margin-right: 13px;
            color: #fff;
            background-color: #717fe0;
            border-radius: 50%;
        }

        .ticket-file-upload-icon i {
            font-size: 20px;
        }

        .ticket-file-upload-text span,
        .ticket-file-upload-text small {
            display: block;
        }

        .ticket-file-upload-text small {
            margin-top: 5px;
            font-size: 11px;
        }

        .ticket-file-input {
            position: absolute;
            inset: 0;
            width: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Footer */
        .ticket-create-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding-top: 25px;
            border-top: 1px solid #e6e6e6;
        }

        .ticket-create-footer p {
            margin: 0;
            font-size: 11px;
        }

        .ticket-create-footer p i {
            margin-right: 4px;
            color: #717fe0;
            font-size: 16px;
            vertical-align: middle;
        }

        .ticket-create-footer .size-116 {
            min-width: 155px;
            height: 46px;
        }

        .btn-back-ticket {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #555555;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .btn-back-ticket:hover {
            background: #222222;
            color: #ffffff;
            border-color: #222222;
        }

        @media (max-width: 767px) {
            .ticket-create-wrapper {
                padding: 25px 20px;
            }

            .ticket-create-head,
            .ticket-create-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .ticket-create-footer button {
                width: 100%;
            }
        }
    </style>
@endsection


@section('content')
    @include('admin.alerts.toast.success')
    @include('admin.alerts.toast.error')

    <section class="bg0 p-t-85 p-b-85">
        <div class="container">

            {{-- Breadcrumb --}}
            <div class="flex-w flex-r-m p-b-35">
                <a href="{{ route('customer.home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Home
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <a href="{{ route('customer.profile.profile') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    My Account
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <a href="{{ route('customer.profile.ticket.index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    My Tickets
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <span class="stext-109 cl4">
                    Submit New Ticket
                </span>
            </div>

            <div class="row">

                {{-- Profile Sidebar --}}
                @include('customer.layouts.partials.profile-sidebar')

                {{-- Create Ticket Content --}}
                <div class="col-lg-9 col-md-8 p-b-30">
                    <div class="bor10 p-lr-40 p-t-35 p-b-40">

                        {{-- Header --}}
                        <div class="ticket-create-head">
                            <div>
                                <h1 class="mtext-111 cl2 p-b-8">
                                    Submit New Ticket
                                </h1>

                                <p class="stext-107 cl6">
                                    Describe your issue and our support team will get back to you as soon as possible.
                                </p>
                            </div>


                            <div class="ticket-view-actions">
                                <a href="{{ route('customer.profile.ticket.index') }}" class="btn-back-ticket">
                                    <i class="zmdi zmdi-arrow-left m-r-6"></i> Back to Tickets
                                </a>
                            </div>

                        </div>

                        <form action="{{ route('customer.profile.ticket.store') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            <div class="row">

                                {{-- Subject --}}
                                <div class="col-12 p-b-25">
                                    <label for="subject" class="ticket-form-label">
                                        Ticket Subject <span>*</span>
                                    </label>

                                    <div class="ticket-input-wrap">
                                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                                            class="ticket-form-input"
                                            placeholder="Example: Problem with payment">
                                    </div>

                                    @error('subject')
                                        <span class="ticket-error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Category --}}
                                <div class="col-md-6 p-b-25">
                                    <label for="category_id" class="ticket-form-label">
                                        Category <span>*</span>
                                    </label>

                                    <div class="ticket-select-wrap">
                                        <select id="category_id" name="category_id"
                                            class="ticket-form-select">
                                            <option value="">Select a category</option>

                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @error('category_id')
                                        <span class="ticket-error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Priority --}}
                                <div class="col-md-6 p-b-25">
                                    <label for="priority_id" class="ticket-form-label">
                                        Priority <span>*</span>
                                    </label>

                                    <div class="ticket-select-wrap">
                                        <select id="priority_id" name="priority_id"
                                            class="ticket-form-select">
                                            <option value="">Select priority</option>

                                            @foreach ($priorities as $priority)
                                                <option value="{{ $priority->id }}" @selected(old('priority_id') == $priority->id)>
                                                    {{ $priority->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @error('priority_id')
                                        <span class="ticket-error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="col-12 p-b-25">
                                    <label for="description" class="ticket-form-label">
                                        Message <span>*</span>
                                    </label>

                                    <textarea id="description" name="description" rows="8"
                                        class="ticket-form-textarea"
                                        placeholder="Please describe your request or problem in detail...">{{ old('description') }}</textarea>

                                    <p class="stext-107 cl6 p-t-10">
                                        Include the order number or product name if your ticket is related to an order.
                                    </p>

                                    @error('description')
                                        <span class="ticket-error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Attachment --}}
                                <div class="col-12 p-b-30">
                                    <label for="image" class="ticket-form-label">
                                        Attachment
                                        <small>(Optional)</small>
                                    </label>

                                    <div class="ticket-file-upload">
                                        <div class="ticket-file-upload-icon">
                                            <i class="zmdi zmdi-attachment-alt"></i>
                                        </div>

                                        <div class="ticket-file-upload-text">
                                            <span class="stext-107 cl2">
                                                Choose an image
                                            </span>

                                            <small class="stext-107 cl6">
                                                JPG, JPEG, PNG files are allowed.
                                            </small>
                                        </div>

                                        <input type="file" id="image" name="image" class="ticket-file-input"
                                            accept=".jpg,.jpeg,.png,.pdf">
                                    </div>

                                    @error('image')
                                        <span class="ticket-error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>

                            {{-- Form Footer --}}
                            <div class="ticket-create-footer">
                                <p class="stext-107 cl6">
                                    <i class="zmdi zmdi-info-outline"></i>
                                    You can track the status of your ticket from the My Tickets section.
                                </p>

                                <button type="submit"
                                    class="flex-c-m stext-101 cl0 size-116 bg1 bor14 hov-btn1 p-lr-15 trans-04">
                                    <i class="zmdi zmdi-mail-send m-r-8"></i>
                                    Submit Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
