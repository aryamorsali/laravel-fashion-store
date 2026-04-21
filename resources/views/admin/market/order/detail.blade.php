@extends('admin.layouts.master2')

@section('head-tag')
    <title>Order Detail</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Order Detail</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Order Detail</h3>
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
                            <th scope="col">Product</th>
                            <th scope="col">Amazing sales percentage</th>
                            <th scope="col">Amazing sales amount</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Total product price</th>
                            <th scope="col">Final amount</th>
                            <th scope="col">Color</th>
                            <th scope="col">Size</th>
                            <th scope="col">Attributes</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($order->orderItems as $item)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $item->productVariant?->product->name ?? '-' }}</td>
                                <td>{{ $item->amazingSale->percentage ?? '-' }}%</td>
                                <td>{{ number_format($item->amazing_sale_discount_amount ?? 0) }}</td>
                                <td>{{ $item->quantity ?? '-' }}</td>
                                <td>{{ number_format(($item->final_product_price ?? 0) * ($item->quantity ?? 1)) }}</td>
                                <td>{{ number_format($item->final_total_price ?? 0) }}</td>
                                <td>{{ $item->color->name ?? '-' }}</td>
                                <td>{{ $item->size->name ?? '-' }}</td>
                                <td>
                                    @php
                                        $product = $item->productVariant?->product;
                                    @endphp
                                    @if ($product && $product->attributeValues->count())
                                       <ul style="list-style: none; padding: 0; margin: 0;">
                                            @foreach ($product->attributeValues as $value)
                                                <li>
                                                    {{ $value->productAttribute->name }} :
                                                    {{ $value->value }}
                                                    {{ $value->productAttribute->unit ?? '' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
        </section>

    </section>
@endsection
