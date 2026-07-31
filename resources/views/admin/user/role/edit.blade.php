@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Role</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">User</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Role</a></li>
                <li class="breadcrumb-item active">Edit Role</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Role</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.user.role.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.user.role.update', $role) }}" method="post">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="name">Role name</label>
                                <input type="text" name="name" id="name" class="form-control form-control-sm"
                                    value="{{ old('name' , $role->name) }}">
                            </div>
                            @error('name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>
                        <section class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" name="description" id="description"
                                    class="form-control form-control-sm" value="{{ old('description' , $role->description) }}">
                            </div>
                            @error('description')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-2 my-2">
                            <button class="btn btn-primary btn-sm mt-md-4">Submit</button>
                        </section>


                    </section>
                </form>
            </section>
        </section>
    @endsection
