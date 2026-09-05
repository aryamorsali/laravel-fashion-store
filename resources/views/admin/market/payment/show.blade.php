@extends('admin.layouts.master2')

@section('head-tag')
    <title>Show Payment</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Comments</a></li>
                <li class="breadcrumb-item active">Show Payment</li>
            </ol>
        </nav>
        <section class="main-body-container">

            <section class="mb-3">
                <h3 class="mt-2">Show Payment</h3>
            </section>

            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.payment.index') }}" class="btn btn-dark btn-sm">Back to Payments</a>
            </section>

            @php
                switch ($payment->status) {
                    case 'paid':
                        $cardClass = 'bg-success text-white';
                        break;
                    case 'failed':
                        $cardClass = 'bg-danger text-white';
                        break;
                    case 'returned':
                        $cardClass = 'bg-warning text-dark';
                        break;
                    case 'unpaid':
                    default:
                        $cardClass = 'bg-secondary text-white';
                        break;
                }
            @endphp

            <section class="card mb-3">
                <section class="card-header {{ $cardClass }}">
                    {{ $payment->user->fullName ?? 'User deleted' }} - ID: {{ $payment->user->id ?? '-' }}
                </section>
                <section class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Amount:</dt>
                        <dd class="col-sm-9">{{ number_format($payment->amount) }}</dd>

                        <dt class="col-sm-3">User Email:</dt>
                        <dd class="col-sm-9">{{ $payment->user->email ?? '-' }}</dd>

                        <dt class="col-sm-3">User Mobile:</dt>
                        <dd class="col-sm-9">{{ $payment->user->mobile ?? '-' }}</dd>

                        <dt class="col-sm-3">Order ID:</dt>
                        <dd class="col-sm-9">
                            @if ($payment->order)
                                <a href="{{ route('admin.market.order.show', $payment->order->id) }}">
                                    {{ $payment->order->id }}
                                </a>
                            @else
                                -
                            @endif
                        </dd>

                        <dt class="col-sm-3">Order Final Amount:</dt>
                        <dd class="col-sm-9">
                            {{ $payment->order ? number_format($payment->order->order_final_amount) : '-' }}</dd>

                        <dt class="col-sm-3">Payment ID:</dt>
                        <dd class="col-sm-9">{{ $payment->id }}</dd>

                        <dt class="col-sm-3">Gateway:</dt>
                        <dd class="col-sm-9">{{ $payment->gateway }}</dd>

                        <dt class="col-sm-3">Transaction ID:</dt>
                        <dd class="col-sm-9">{{ $payment->transaction_id ?? '-' }}</dd>

                        <dt class="col-sm-3">Status:</dt>
                        <dd class="col-sm-9 text-capitalize">{{ $payment->status }}</dd>

                        <dt class="col-sm-3">Paid At:</dt>
                        <dd class="col-sm-9">{{ $payment->paid_at }}</dd>
                    </dl>

                    <a href="{{ route('admin.market.payment.changePaymentStatus', $payment) }}"
                        class="btn btn-info mt-3">
                        <i class="fas fa-exchange-alt"></i> Change Status
                    </a>
                </section>
            </section>



        </section>
    @endsection
