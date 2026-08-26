@extends('admin.layouts.master2')

@section('head-tag')
    <title>Admin Permissions</title>
    <style>
        .page-description {
            margin: 0;
            color: #888;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">User</a></li>
                <li class="breadcrumb-item active">Admin Permissions</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Admin Permissions</h3>
                <p class="page-description">
                    These permissions are through added permissions or roles.
                </p>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.user.admin.index') }}" class="btn btn-dark btn-sm">Back</a>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="col-12">
                <section class="row mt-1 py-2">

                    @foreach ($permissions as $permission)
                        <section class="col-md-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" disabled checked>
                                <label class="form-check-label mr-3 mt-1">{{ $permission->name }}</label>
                            </div>
                        </section>
                    @endforeach

                </section>
            </section>
        </section>

    </section>
@endsection
