@extends('admin.layouts.master2')

@section('head-tag')
    <title>Orders</title>
    <style>
        table th {
            font-size: 13.5px;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Orders</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
            </section>


            <section class="table-responsive" style="min-height: 50vh;">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th>Order code</th>
                            <th>Total order amount (without discount)</th>
                            <th>Total of all discounts</th>
                            <th>Discount amount (all products)</th>
                            <th>Final amount</th>
                            <th>Payment status</th>
                            <th>bank</th>
                            <th>Shipping status</th>
                            <th>Shipping method</th>
                            <th>Order status</th>
                            <th class="max-width-14-rem text-center"><i class="fa fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($orders as $order)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $order->id }}</td>
                                <td>{{ number_format($order->order_final_amount) }}</td>
                                <td>{{ number_format($order->order_discount_amount) }}</td>
                                <td>{{ number_format($order->order_total_products_discount_amount) }}</td>
                                <td>{{ number_format($order->order_final_amount - $order->order_discount_amount) }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td>{{ $order->payments->where('status', 'paid')->last()->gateway ?? '-' }}</td>
                                <td>{{ $order->delivery_status }}</td>

                                <td>{{ $order->delivery->name ?? '-'}}</td>
                                <td>{{ $order->order_status_value }}</td>

                                <td class="width-14-rem text-center">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-success btn-sm btn-block dropdown-toggle"
                                            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa fa-tools"></i> Operation
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li>
                                                <a href="{{ route('admin.market.order.show', $order) }}"
                                                    class="dropdown-item text-right">
                                                    <i class="fa-solid fa-file-invoice"></i> View the invoice
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.market.order.changeSendStatus', $order) }}"
                                                    class="dropdown-item text-right"><i class="fa-solid fa-truck"></i>
                                                    Change the sending status</a>
                                            </li>

                                            <li>
                                                <a href="{{ route('admin.market.order.changeOrderStatus', $order) }}"
                                                    class="dropdown-item text-right">
                                                    <i class="fa fa-sync"></i> Change order status
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.market.order.cancelOrder', $order) }}"
                                                    class="dropdown-item text-right">
                                                    <i class="fa-solid fa-ban"></i> Cancel the order
                                                </a>

                                            </li>
                                        </ul>
                                    </div>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            </section>
        </section>

    </section>
@endsection
