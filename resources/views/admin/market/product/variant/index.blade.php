@extends('admin.layouts.master2')

@section('head-tag')
    <title>Product Variants</title>
@endsection
@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Product</a></li>
                <li class="breadcrumb-item active">Variants</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Product Variants</h3> <small class="text-muted mb-3">(color & size combinations)</small>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-1 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <section class="d-flex align-items-center mt-3 mb-3 pb-2 gap-2">
                    <a href="{{ route('admin.market.product.index') }}" class="btn btn-dark btn-sm">Cancel</a>

                    @if ($product->variants->isNotEmpty())
                        <form class="d-inline" action="{{ route('admin.market.variant.destroyAllVariants', $product) }}"
                            method="post">
                            @csrf
                            @method('delete')
                            <button class="btn btn-warning btn-sm delete" type="submit">Delete all variants</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.market.variant.create', $product) }}" class="btn btn-dark btn-sm my_btn">Add
                        new variant</a>
                </section>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product</th>
                            <th scope="col">Color</th>
                            <th scope="col">Size</th>
                            <th scope="col">Price</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($product->variants as $variant)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $product->name }}</td>
                                <td>
                                    {{ $variant->color->name }}
                                    <div
                                        style="width: 15px; height: 15px; border-radius: 3px; background-color: {{ $variant->color->hex_code }}; display: inline-block; vertical-align: middle;">
                                    </div>
                                </td>

                                <td>{{ $variant->size->name ?? '-' }}</td>
                                <td>${{ rtrim(rtrim(number_format($variant->price, 2), '0'), '.') }}</td>

                                <td class="width-16-rem text-center">
                                    <a href="{{ route('admin.market.variant.edit', ['product' => $product, 'variant' => $variant]) }}"
                                        class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                        Edit</a>
                                    <form class="d-inline"
                                        action="{{ route('admin.market.variant.destroy', ['product' => $product, 'variant' => $variant]) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm width-6-rem mi delete" type="submit"><i
                                                class="fa fa-trash-alt"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
        </section>

    </section>
@endsection

@section('script')
    @include('admin.alerts.sweetalert.delete-confirm', ['className' => 'delete'])
@endsection
