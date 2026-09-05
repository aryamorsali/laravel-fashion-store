@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create menu</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">content</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">menus</a></li>
                <li class="breadcrumb-item active">create menu</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Menu</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.content.menu.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.content.menu.store') }}" method="post">
                    @csrf
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name"
                                    value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="parent_id">Parent menu</label>
                                <select name="parent_id" class="form-control form-control-sm" id="parent_id">
                                    <option value="">Main menu</option>
                                    @foreach ($parent_menus as $menu)
                                        <option value="{{ $menu->id }}"
                                            @if (old('parent_id') == $menu->id) selected @endif>
                                            {{ $menu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('parent_id')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                            <section class="col-12 col-md-6 my-3">
                                <div class="form-group">
                                    <label for="url">Url address</label>
                                    <input type="text" class="form-control form-control-sm" name="url" id="url"
                                        value="{{ old('url') }}">
                                </div>
                                @error('url')
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
