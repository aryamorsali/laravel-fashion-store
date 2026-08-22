@extends('customer.layouts.app')

@section('head-tag')
    <title>Profile</title>

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

        .profile-avatar {
            width: 58px;
            height: 58px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #717fe0;
            color: #fff;
            font-size: 22px;
            font-weight: 700;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            border-radius: inherit;
        }

        .profile-readonly-field {
            height: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 18px;
            background: #f8f8f8;
            border: 1px solid #e6e6e6;
            color: #777;
            font-size: 14px;
        }

        .profile-readonly-field i {
            color: #717fe0;
            font-size: 18px;
        }

        .profile-quick-card {
            min-height: 98px;
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            border: 1px solid #e6e6e6;
            color: #555;
            transition: all .25s ease;
        }

        .profile-quick-card:hover {
            color: #555;
            border-color: #717fe0;
            box-shadow: 0 6px 20px rgba(0, 0, 0, .07);
            transform: translateY(-2px);
        }

        .profile-quick-card i {
            color: #717fe0;
            font-size: 28px;
        }

        .profile-quick-card h5 {
            margin: 0 0 4px;
            color: #333;
            font-size: 15px;
            font-weight: 700;
        }

        .profile-quick-card span {
            color: #888;
            font-size: 12px;
        }

        @media (max-width: 767.98px) {
            .bor10.p-lr-40 {
                padding-left: 20px;
                padding-right: 20px;
            }

            .profile-avatar {
                margin-top: 15px;
            }

            .profile-form-actions {
                padding-top: 10px;
            }

            .profile-form-actions>div {
                margin-bottom: 10px;
            }
        }


        .profile-form-actions {
            margin-left: -5px;
            margin-right: -5px;
        }

        .profile-form-actions>div {
            padding-left: 5px;
            padding-right: 5px;
        }

        .profile-action-btn {
            width: 100%;
            min-height: 46px;
            border: 0;
            cursor: pointer;
        }

        ///////////////////////////////////////////

        .profile-file-box {
            width: 100%;
            height: 47px;
            display: flex;
            align-items: center;
            overflow: hidden;
            box-sizing: border-box;
            border: 1px solid #e6e6e6;
            background-color: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .profile-file-input {
            width: 100%;
            height: 100%;
            display: block;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: Poppins, sans-serif;
            font-size: 13px;
            color: #555;
            line-height: 45px;
            border: 0;
            outline: none;
            background-color: transparent;
            cursor: pointer;
        }

        .profile-file-input::file-selector-button {
            height: 45px;
            padding: 0 14px;
            margin-right: 12px;

            font-family: Poppins, sans-serif;
            font-size: 13px;
            color: #333;
            cursor: pointer;
            border: 0;
            border-right: 1px solid #e6e6e6;
            background-color: #f7f7f7;
            transition: all 0.2s ease;
        }

        .profile-file-input::-webkit-file-upload-button {
            height: 45px;
            padding: 0 14px;
            margin-right: 12px;
            font-family: Poppins, sans-serif;
            font-size: 13px;
            color: #333;
            cursor: pointer;
            border: 0;
            border-right: 1px solid #e6e6e6;
            background-color: #f7f7f7;
        }

        .profile-file-input:hover::file-selector-button {
            color: #fff;
            background-color: #6e6e6f;
            border-color: #6e6e6f;
        }

        .profile-file-input:hover::-webkit-file-upload-button {
            color: #fff;
            background-color: #6e6e6f;
            border-color: #6e6e6f;
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

                <span class="stext-109 cl4">
                    My Account
                </span>
            </div>

            <div class="row">
                @include('customer.layouts.partials.profile-sidebar')

                {{-- Main content --}}
                <div class="col-lg-9 col-md-8">
                    <div class="bor10 p-lr-40 p-t-35 p-b-40">

                        <div class="flex-w flex-sb-m p-b-25 bor12">
                            <div>
                                <h3 class="mtext-111 cl2 p-b-8">
                                    My Profile
                                </h3>

                                <p class="stext-109 cl6">
                                    Manage your personal information and account details.
                                </p>
                            </div>

                            <div class="profile-avatar">
                                @if (auth()->user()->profile_photo_path)
                                    <img src="{{ asset(auth()->user()->profile_photo_path) }}" alt="UserAvatar">
                                @else
                                    {{ strtoupper(mb_substr(auth()->user()->first_name, 0, 1)) }}
                                @endif
                            </div>
                        </div>

                     

                        {{-- Validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger m-t-25">
                                <ul class="m-b-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('customer.profile.update-profile') }}" method="POST" class="p-t-30"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- First Name --}}
                                <div class="col-md-6 p-b-20">
                                    <label for="first_name" class="stext-107 cl2 p-b-10">
                                        First Name <span class="text-danger">*</span>
                                    </label>

                                    <div class="bor8 bg0">
                                        <input id="first_name" type="text" name="first_name"
                                            value="{{ old('first_name', auth()->user()->first_name) }}"
                                            class="stext-111 cl2 plh3 size-116 p-lr-20">
                                    </div>

                                    @error('first_name')
                                        <small class="text-danger d-block p-t-7">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                {{-- Last Name --}}
                                <div class="col-md-6 p-b-20">
                                    <label for="last_name" class="stext-107 cl2 p-b-10">
                                        Last Name <span class="text-danger">*</span>
                                    </label>

                                    <div class="bor8 bg0">
                                        <input id="last_name" type="text" name="last_name"
                                            value="{{ old('last_name', auth()->user()->last_name) }}"
                                            class="stext-111 cl2 plh3 size-116 p-lr-20">
                                    </div>

                                    @error('last_name')
                                        <small class="text-danger d-block p-t-7">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>

                                {{-- National Code --}}
                                <div class="col-md-6 p-b-20">
                                    <label for="mobile" class="stext-107 cl2 p-b-10">
                                        National Code
                                    </label>

                                    <div class="bor8 bg0">
                                        <input id="national_code" type="text" name="national_code"
                                            value="{{ old('national_code', auth()->user()->national_code) }}"
                                            class="stext-111 cl2 plh3 size-116 p-lr-20">
                                    </div>

                                    @error('national_code')
                                        <small class="text-danger d-block p-t-7">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>


                                {{-- User Avatar --}}
                                <div class="col-md-6 p-b-20">
                                    <label for="profile_photo_path" class="stext-107 cl2 p-b-10">
                                        User Avatar
                                    </label>

                                    <div class="bor8 bg0 profile-file-box">
                                        <input type="file" name="profile_photo_path" id="profile_photo_path"
                                            class="profile-file-input">
                                    </div>

                                    @error('profile_photo_path')
                                        <small class="text-danger d-block p-t-7">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>


                            </div>

                            <div class="row p-t-30 profile-form-actions">
                                <div class="col-12 col-sm-6 m-b-10">
                                    <a href="{{ route('customer.home') }}"
                                        class="flex-c-m stext-101 cl0 bg3 bor2 hov-btn3 p-lr-15 trans-04 profile-action-btn">
                                        Cancel
                                    </a>
                                </div>

                                <div class="col-12 col-sm-6 m-b-10">
                                    <button type="submit"
                                        class="flex-c-m stext-101 cl0 bg1 bor2 hov-btn1 p-lr-15 trans-04 profile-action-btn">
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Account quick actions --}}
                    <div class="row p-t-30">
                        {{-- My Orders --}}
                        <div class="col-12 col-md-6 col-lg-3 m-b-20">
                            <a href="#" class="profile-quick-card">
                                <i class="zmdi zmdi-shopping-basket"></i>

                                <div>
                                    <h5>My Orders</h5>
                                    <span>Track your purchases</span>
                                </div>
                            </a>
                        </div>

                        {{-- My Addresses --}}
                        <div class="col-12 col-md-6 col-lg-3 m-b-20">
                            <a href="#" class="profile-quick-card">
                                <i class="zmdi zmdi-pin"></i>

                                <div>
                                    <h5>My Addresses</h5>
                                    <span>Manage addresses</span>
                                </div>
                            </a>
                        </div>

                        {{-- My Tickets --}}
                        <div class="col-12 col-md-6 col-lg-3 m-b-20">
                            <a href="#" class="profile-quick-card">
                                <i class="zmdi zmdi-headset-mic"></i>

                                <div>
                                    <h5>My Tickets</h5>
                                    <span>Contact support team</span>
                                </div>
                            </a>
                        </div>

                        {{-- Wishlist --}}
                        <div class="col-12 col-md-6 col-lg-3 m-b-20">
                            <a href="#" class="profile-quick-card">
                                <i class="zmdi zmdi-favorite-outline"></i>

                                <div>
                                    <h5>Wishlist</h5>
                                    <span>View favorite products</span>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
