@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create User</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Users</a></li>
                <li class="breadcrumb-item active">Create User</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create User</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.user.customer.index') }}" class="btn btn-dark btn-sm">Back</a>
            </section>

            <section>
                <form action="{{ route('admin.user.customer.store') }}" method="post" enctype="multipart/form-data"
                    id="form">
                    @csrf
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="first_name">First name</label>
                                <input type="text" class="form-control form-control-sm" name="first_name" id="first_name"
                                    value="{{ old('first_name') }}">
                            </div>
                            @error('first_name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="last_name">Last name</label>
                                <input type="text" class="form-control form-control-sm" name="last_name" id="last_name"
                                    value="{{ old('last_name') }}">
                            </div>
                            @error('last_name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control form-control-sm" name="email" id="email"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" class="form-control form-control-sm" name="mobile" id="mobile"
                                    value="{{ old('mobile') }}">
                            </div>
                            @error('mobile')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" class="form-control form-control-sm" name="password" id="password"
                                    value="{{ old('password') }}">
                            </div>
                            @error('password')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="password_confirimation">Confirm password</label>
                                <input type="text" class="form-control form-control-sm" name="password_confirmation"
                                    id="password_confirmation">
                            </div>
                            @error('password_confirimation')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="profile_photo_path">Image</label>
                                <input type="file" class="form-control form-control-sm" name="profile_photo_path" id="profile_photo_path">
                            </div>
                            @error('profile_photo_path')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="activation">activation</label>
                                <select name="activation" class="form-control form-control-sm" id="activation">
                                    <option value="0" @if (old('activation') == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('activation') == 1) selected @endif>active
                                    </option>
                                </select>
                            </div>
                            @error('activation')
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
