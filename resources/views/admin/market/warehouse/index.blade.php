@extends('admin.layouts.master2')

@section('head-tag')
    <title>Warehouses</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Warehouses</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Warehouses</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                @can('create-warehouse')
                    <a href="{{ route('admin.market.warehouse.create') }}" class="btn btn-dark btn-sm my-btn ">Create new
                        warehouse</a>
                @endcan

            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            @canany(['update-warehouse', 'view-inventory', 'delete-warehouse'])
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($warehouses as $warehouse)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $warehouse->name }}</td>
                                <td>{{ $warehouse->address ?? '-' }}</td>
                                @canany(['update-warehouse', 'view-inventory', 'delete-warehouse'])
                                    <td class="width-20-rem text-center">
                                        @can('view-inventory')
                                            <a href="{{ route('admin.market.warehouse.variant.index', $warehouse) }}"
                                                class="btn btn-warning btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                                Inventory</a>
                                        @endcan
                                        @can('update-warehouse')
                                            <a href="{{ route('admin.market.warehouse.edit', $warehouse) }}"
                                                class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                                Edit</a>
                                        @endcan

                                        @can('delete-warehouse')
                                            <form class="d-inline"
                                                action="{{ route('admin.market.warehouse.destroy', $warehouse) }}" method="post">
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
