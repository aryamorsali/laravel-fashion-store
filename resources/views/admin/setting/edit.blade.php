@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Setting</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Settings</a></li>
                <li class="breadcrumb-item active">Edit Setting</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Setting</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.setting.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.setting.update', $setting) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="key">Key</label>
                                <input type="text" class="form-control form-control-sm" name="key" id="key"
                                    value="{{ old('key', $setting->key) }}" readonly
                                    style="background-color: white;
                                        color: #333;
                                        border: 1px solid #c7d2fe;
                                        border-radius: 6px;
                                        padding: 8px 12px;
                                        font-size: 14px;
                                        cursor: default;">
                            </div>

                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="value">Value</label>

                                @if ($setting->key === 'site_logo')
                                    <input type="file" class="form-control form-control-sm" name="value"
                                        id="value" style="padding: 8px 12px;">
                                @else
                                    <input type="text" class="form-control form-control-sm" name="value" id="value"
                                        value="{{ old('value', $setting->value) }}" style="padding: 8px 12px;">
                                @endif
                            </div>
                            @error('value')
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
