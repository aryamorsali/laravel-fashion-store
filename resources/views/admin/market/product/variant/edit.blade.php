@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Variant</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">product</a></li>
                <li class="breadcrumb-item active">edit variant</li>
            </ol>
        </nav>
        <section class="main-body-container">
            @include('admin.alerts.alert-section.warning')

            <section>
                <h3 class="mt-2">Edit Variant</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.variant.index', $product) }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.variant.update', ['product' => $product, 'variant' => $variant]) }}"
                    method="post">
                    @csrf
                    @method('put')
                    <section class="row">

                        <section class="col-12 col-md-6 mt-3">
                            <div class="form-group">
                                <label>Product</label>
                                <p
                                    style="background-color: white; color: #333;  border: 1px solid #c7d2fe; border-radius: 6px; padding: 6px 12px; font-size: 14px;">
                                    {{ $product->name }}
                                </p>
                            </div>
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ $variant->color->name ?? '-' }}" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label>Size</label>
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ $variant->size->name ?? '-' }}" readonly
                                    style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control form-control-sm" name="price" id="price"
                                    value="{{ old('price', number_format($variant->price, 0, '', '') ) }}">
                            </div>
                            @error('price')
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
