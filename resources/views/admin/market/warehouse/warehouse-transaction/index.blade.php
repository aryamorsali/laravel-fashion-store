@extends('admin.layouts.master2')

@section('head-tag')
    <title>Warehouse Transaction</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Warehouse Transaction</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Warehouse Transaction</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col">Product</th>
                            <th scope="col">Color</th>
                            <th scope="col">Size</th>
                            <th scope="col">Type</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Date</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($transactions as $transaction)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $transaction->warehouse->name ?? '-'}}</td>

                                <td> {{ $transaction->productVariant->product->name ?? '-' }}</td>
                                <td> {{ $transaction->productVariant->color->name ?? '-' }}</td>
                                <td> {{ $transaction->productVariant->size->name ?? '-' }}</td>
                                <td> {{ $transaction->type }}</td>
                                <td> {{ $transaction->quantity }}</td>
                                <td> {{ number_format($transaction->unit_price) }}</td>
                                <td> {{ $transaction->created_at }}</td>


                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
        </section>

    </section>
@endsection

