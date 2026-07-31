@extends('admin.layouts.master2')

@section('head-tag')
    <title>Role access</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Users</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Roles</a></li>
                <li class="breadcrumb-item active">Role access</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Role access</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.user.role.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.user.role.permission.update', $role) }}" method="post">
                    @csrf
                    <section class="row">

                        <section class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="name">Role name</label>
                                <input type="text" id="name" class="form-control form-control-sm"
                                    value="{{ old('name', $role->name) }}" readonly
                                    style="background-color: white;
                                        color: #333;
                                        border: 1px solid #c7d2fe;
                                        border-radius: 6px;
                                        padding: 8px 12px;
                                        font-size: 14px;
                                        cursor: default;">
                            </div>

                        </section>
                        <section class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="description">توضیح نقش</label>
                                <input type="text"  id="description"class="form-control form-control-sm"
                                    value="{{ old('description', $role->description) }}" readonly
                                    style="background-color: white;
                                        color: #333;
                                        border: 1px solid #c7d2fe;
                                        border-radius: 6px;
                                        padding: 8px 12px;
                                        font-size: 14px;
                                        cursor: default;">
                            </div>
                        </section>

                        <section class="col-12">
                            <section class="row mt-1 py-2">

                                @php
                                    $rolePermissionsArray = $role->permissions->pluck('id')->toArray();
                                @endphp

                                @foreach ($permissions as $key => $permission)
                                    <section class="col-md-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="permissions[]"
                                                value="{{ $permission->id }}" id="{{ $permission->id }}"
                                                @if (in_array($permission->id, $rolePermissionsArray)) checked @endif>
                                            <label for="{{ $permission->id }}"
                                                class="form-check-label mr-3 mt-1">{{ $permission->name }}</label>
                                        </div>
                                        <div class="mt-2">
                                            @error('permissions.' . $key)
                                                <div class="text-danger"
                                                    style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </section>
                                @endforeach

                            </section>
                        </section>

                        <section class="col-12 col-md-2">
                            <button class="btn btn-primary btn-sm mt-md-4">Submit</button>
                        </section>

                    </section>
                </form>
            </section>
        </section>
    @endsection
