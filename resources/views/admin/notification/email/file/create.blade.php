@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Email notification file</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Notitfication</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Email notification</a></li>
                <li class="breadcrumb-item active">Create Email notification file</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create email notification file</h3>
            </section>
            @include('admin.alerts.alert-section.error')
            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <a href="{{ route('admin.notification.email.file.index', $email) }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.notification.email.file.store', $email) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <section class="row">

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="file">File</label>
                                <input type="file" class="form-control form-control-sm" name="file" id="file">
                            </div>
                            @error('file')
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
