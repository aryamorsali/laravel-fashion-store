@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Banner</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Content</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Banner</a></li>
                <li class="breadcrumb-item active">Create Banner</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Banner</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.content.banner.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.content.banner.store') }}" method="post" enctype="multipart/form-data"
                    id="form">
                    @csrf
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control form-control-sm" name="title" id="title"
                                    placeholder="Small text above(optional)" value="{{ old('title') }}">
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
                                    placeholder="Large text(optional)" value="{{ old('subtitle') }}">
                            </div>
                            @error('subtitle')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="button_text">Button text</label>
                                <input type="text" class="form-control form-control-sm" name="button_text"
                                    id="button_text" placeholder="optioal" value="{{ old('button_text') }}">
                            </div>
                            @error('button_text')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="button_url">Button url</label>
                                <input type="text" class="form-control form-control-sm" name="button_url" id="button_url"
                                    placeholder="optioal" value="{{ old('button_url') }}">
                            </div>
                            @error('button_url')
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
                                <label for="status">Status</label>
                                <select name="status" class="form-control form-control-sm" id="status">
                                    <option value="0" @if (old('status') == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('status') == 1) selected @endif>active
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
