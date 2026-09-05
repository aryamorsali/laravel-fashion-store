@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Attribute</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Product Attribute</a></li>
                <li class="breadcrumb-item active">create attribute</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Attribute</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.property.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.property.store') }}" method="post">
                    @csrf
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="name">Name</label>
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
                                <label for="unit">Unit</label>
                                <input type="text" class="form-control form-control-sm" name="unit" id="unit"
                                    placeholder="unit of measurement (optional)" value="{{ old('unit') }}">
                            </div>
                            @error('unit')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control form-control-sm" id="category_id"
                                    {{ old('is_global', $productAttribute->is_global ?? false) ? 'disabled' : '' }}>
                                    <option value="">Select a category</option>
                                    @foreach ($productCategories as $category)
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

                        <div class="form-group mt-2">
                            <div class="form-check">
                                <input type="checkbox" name="is_global" id="is_global" value="1"
                                    class="form-check-input"
                                    {{ old('is_global', $productAttribute->is_global ?? false) ? 'checked' : '' }}
                                    onchange="category_id.disabled=this.checked; if(this.checked) category_id.value=''">
                                <label for="is_global" class="form-check-label">Active for all categories</label>
                            </div>
                        </div>

                        <section class="col-12 my-3 d-flex justify-content-end">
                            <button class="btn btn-primary">Submit</button>
                        </section>

                    </section>
                </form>
            </section>
        </section>
    @endsection
