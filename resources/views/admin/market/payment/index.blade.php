@extends('admin.layouts.master2')

@section('head-tag')
    <title>Payments</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Payments</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Payments</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <section class="sort">

                    <span>Sort by : </span>
                    <a class="btn {{ request()->routeIs('admin.market.payment.index') ? 'btn-info' : 'btn-light' }} btn-sm px-1 py-1"
                        href="{{ route('admin.market.payment.index') }}">
                        All Payments
                    </a>
                    <a class="btn {{ request()->sort == 1 ? 'btn-info' : 'btn-light' }} btn-sm px-1 py-1"
                        href="{{ route('admin.market.payment.filter', [
                            'sort' => '1',
                        ]) }}">Paid</a>
                    <a class="btn {{ request()->sort == 2 ? 'btn-info' : 'btn-light' }} btn-sm px-1 py-1"
                        href="{{ route('admin.market.payment.filter', [
                            'sort' => '2',
                        ]) }}">Unpaid</a>
                    <a class="btn {{ request()->sort == 3 ? 'btn-info' : 'btn-light' }} btn-sm px-1 py-1"
                        href="{{ route('admin.market.payment.filter', [
                            'sort' => '3',
                        ]) }}">Failed</a>
                    <a class="btn {{ request()->sort == 4 ? 'btn-info' : 'btn-light' }} btn-sm px-1 py-1"
                        href="{{ route('admin.market.payment.filter', [
                            'sort' => '4',
                        ]) }}">returned</a>
                </section>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">User</th>
                            <th scope="col">Gateway</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Paid At</th>
                            <th scope="col">Payment Status</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($payments as $payment)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $payment->transaction_id }}</td>
                                <td>{{ $payment->user->full_name }}</td>
                                <td>{{ $payment->gateway }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>{{ $payment->paid_at }}</td>
                                <td>{{ $payment->status }}</td>

                                <td class="width-16-rem text-center">
                                    <a href="{{ route('admin.market.payment.show', $payment->id) }}"
                                        class="btn btn-primary btn-sm width-6-rem mi">
                                        <i class="fas fa-eye"></i> Show
                                    </a>

                                    <a href="{{ route('admin.market.payment.changePaymentStatus', $payment) }}"
                                        class="btn btn-warning btn-sm width-8-rem mi">
                                        <i class="fas fa-exchange-alt"></i> Change Status
                                    </a>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>

            </section>
        </section>

    </section>
@endsection
