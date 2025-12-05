@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Box</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Home Box</a></li>
                <li class="breadcrumb-item active">Create Box</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Box</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.home-box.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.home-box.store') }}" method="post" enctype="multipart/form-data"
                    id="form">
                    @csrf
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control form-control-sm" name="title" id="title"
                                    value="{{ old('title') }}">
                            </div>
                            @error('title')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="subtitle">Subtitle</label>
                                <input type="text" class="form-control form-control-sm" name="subtitle" id="subtitle"
                                    placeholder="optional" value="{{ old('subtitle') }}">
                            </div>
                            @error('subtitle')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control form-control-sm" id="category_id">
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            @if (old('category_id') == $category->id) selected @endif>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>



                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control form-control-sm" name="image" id="image">
                            </div>
                            @error('image')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" class="form-control form-control-sm" name="url" id="url"
                                    placeholder="Optional, e.g., https://yourWebsite.com" value="{{ old('url') }}">
                            </div>
                            @error('url')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>
                                        {{ $message }}. لطفاً URL کامل وارد کنید، شامل http:// یا https://. مثال:
                                        https://test.com
                                    </strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control form-control-sm" id="status">
                                    <option value="0" @if (old('status') == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('status') == 1 || old('status') == null) selected @endif>active
                                    </option>
                                </select>
                            </div>
                            @error('status')
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
