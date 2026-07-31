@extends('admin.layouts.master2')

@section('head-tag')
    <title>Product Attribute Value</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Product Attribute Value</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Product Attribute Value</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <a href="{{ route('admin.market.property.index', $productAttribute) }}"
                    class="btn btn-info btn-sm me-1 my-btn">Back</a>

                @can('create-product-attribute-value')
                    <a href="{{ route('admin.market.value.create', $productAttribute) }}"
                        class="btn btn-dark btn-sm my-btn ">Create product
                        attribute value</a>
                @endcan

            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product</th>
                            <th scope="col">Attribute name</th>
                            <th scope="col">Value</th>
                            @canany(['update-product-attribute-value', 'delete-product-attribute-value'])
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Actions</th>
                            @endcanany

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($productAttribute->values as $value)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $value->product->name }}</td>

                                <td>{{ $productAttribute->name }}</td>
                                <td>{{ $value->value }}</td>

                                @canany(['update-product-attribute-value', 'delete-product-attribute-value'])
                                    <td class="width-20-rem text-center">
                                        @can('update-product-attribute-value')
                                            <a href="{{ route('admin.market.value.edit', ['productAttribute' => $productAttribute, 'value' => $value]) }}"
                                                class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                                Edit</a>
                                        @endcan

                                        @can('delete-product-attribute-value')
                                            <form class="d-inline"
                                                action="{{ route('admin.market.value.destroy', ['productAttribute' => $productAttribute, 'value' => $value]) }}"
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
