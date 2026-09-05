@extends('admin.layouts.master2')

@section('head-tag')
    <title>Product Attribute</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Product Attribute</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Product Attribute</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                @can('create-product-attribute')
                    <a href="{{ route('admin.market.property.create') }}" class="btn btn-dark btn-sm my-btn ">Create new
                        attribute</a>
                @endcan

            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Attribute</th>
                            <th scope="col">Unit</th>
                            <th scope="col">Category</th>
                            @canany(['update-product-attribute', 'delete-product-attribute',
                                'view-product-attribute-value'])
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Actions</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($productAttributes as $productAttribute)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $productAttribute->name }}</td>
                                <td>{{ $productAttribute->unit ?? '-' }}</td>
                                <td>
                                    {{ $productAttribute->category->name ?? ($productAttribute->is_global ? 'Active for all categories' : '-') }}
                                </td>

                                @canany(['update-product-attribute', 'delete-product-attribute',
                                    'view-product-attribute-value'])
                                    <td class="width-20-rem text-center">
                                        @can('view-product-attribute-value')
                                            <a href="{{ route('admin.market.value.index', $productAttribute) }}"
                                                class="btn btn-warning btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                                Values</a>
                                        @endcan
                                        @can('update-product-attribute')
                                            <a href="{{ route('admin.market.property.edit', $productAttribute) }}"
                                                class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                                Edit</a>
                                        @endcan
                                        @can('delete-product-attribute')
                                            <form class="d-inline"
                                                action="{{ route('admin.market.property.destroy', $productAttribute) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger btn-sm width-6-rem mi delete" type="submit"><i
                                                        class="fa fa-trash-alt"></i> Delete</button>
                                            </form>
                                        @endcan

                                    </td>
                                @endcanany

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
