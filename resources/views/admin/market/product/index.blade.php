@extends('admin.layouts.master2')

@section('head-tag')
    <title>Products</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">content</a></li>
                <li class="breadcrumb-item active">products</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Products</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <a href="{{ route('admin.market.product.create') }}" class="btn btn-dark btn-sm my-btn ">Create new
                    product</a>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Image</th>
                            <th scope="col">Price</th>
                            <th scope="col">Category</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($products as $product)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <img class="rounded"
                                        src="{{ asset($product->image['indexArray'][$product->image['currentImage']]) }}"
                                        alt="" width="70" height="65">
                                </td>
                                <td>${{ rtrim(rtrim(number_format($product->base_price, 2), '0'), '.') }}</td>
                                <td>{{ $product->productCategory->name ?? '-' }}</td>

                                <td class="width-13-rem text-left">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-success btn-sm btn-block dropdown-toggle"
                                            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa fa-tools"></i> Operation
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li>
                                                <a href="{{ route('admin.market.gallery.index', $product) }}"
                                                    class="dropdown-item text-right"><i class="fa fa-images"></i>
                                                    Gallery</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.market.variant.index', $product) }}"
                                                    class="dropdown-item text-right">
                                                    <i class="fa fa-edit"></i> Product Vriant
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.market.product.edit', $product) }}"
                                                    class="dropdown-item text-right">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form class="d-inline"
                                                    action="{{ route('admin.market.product.destroy', $product) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="dropdown-item text-right delete">
                                                        <i class="fa fa-window-close"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
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
