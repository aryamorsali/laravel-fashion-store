@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Admin</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Users</a></li>
                <li class="breadcrumb-item active">Edit Admin</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Admin</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.user.admin.index') }}" class="btn btn-dark btn-sm">Back</a>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section>
                <form action="{{ route('admin.user.admin.update', $admin) }}" method="post" enctype="multipart/form-data"
                    id="form">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="first_name">First name</label>
                                <input type="text" class="form-control form-control-sm" name="first_name" id="first_name"
                                    value="{{ old('first_name', $admin->first_name) }}">
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
                                    value="{{ old('last_name', $admin->last_name) }}">
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
                                    value="{{ old('email', $admin->email) }}">
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
                                    value="{{ old('mobile', $admin->mobile) }}">
                            </div>
                            @error('mobile')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="password">New password</label>
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="(optional)">
                                <small class="text-muted">If left blank, the current password will not be changed.</small>
                            </div>
                            @error('password')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="password_confirmation">Confirm password</label>
                                <input type="text" class="form-control form-control-sm" name="password_confirmation"
                                    id="password_confirmation">
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="profile_photo_path">Image</label>
                                <input type="file" class="form-control form-control-sm" name="profile_photo_path"
                                    id="profile_photo_path">
                                <img src="{{ asset($admin->profile_photo_path) }}" width="90" height="90"
                                    class="mt-3 border rounded">

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
                                    <option value="0" @if (old('activation', $admin->activation) == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('activation', $admin->activation) == 1) selected @endif>active
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
