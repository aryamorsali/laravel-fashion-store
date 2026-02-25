@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Stock</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Warehouse</a></li>
                <li class="breadcrumb-item active">Edit Stock</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Stock</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.warehouse.variant.index', $warehouse) }}"
                    class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form
                    action="{{ route('admin.market.warehouse.variant.update', ['warehouse' => $warehouse, 'warehouseVariant' => $warehouseVariant]) }}"
                    method="post">
                    @csrf
                    @method('put')
                    <section class="col-12 mt-3">
                        <div class="form-group">
                            <label>Warehouse</label>
                            <p
                                style="background-color: white; color: #333;  border: 1px solid #c7d2fe; border-radius: 6px; padding: 6px 12px; font-size: 14px;">
                                {{ $warehouse->name }}
                            </p>
                        </div>
                    </section>

                    {{-- Select Variant --}}
                    <section class="col-12 my-3">
                        <div class="form-group">
                            <label>Product Variant</label>

                                <p style="background-color: white; color: #333;  border: 1px solid #c7d2fe; border-radius: 6px; padding: 6px 12px; font-size: 14px;">  
                                    {{ $warehouseVariant->productVariant->product->name }} - {{ $warehouseVariant->productVariant->color?->name }} -
                                    {{ $warehouseVariant->productVariant->size?->name }}
                                </p>
                        </div>

                    </section>

                    <section class="col-12 my-3">
                        <div class="form-group">
                            <label for="stock">Quantity</label>
                            <input type="text" class="form-control form-control-sm" name="stock" id="stock"
                                value="{{ old('stock', $warehouseVariant->stock) }}">
                        </div>
                        @error('stock')
                            <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </section>

                    <section class="col-12 my-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </section>

            </section>
            </form>
        </section>
    </section>
@endsection
