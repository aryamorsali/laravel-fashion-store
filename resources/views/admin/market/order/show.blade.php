@extends('admin.layouts.master2')

@section('head-tag')
    <title>Invoice</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Invoice</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Invoice</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')


            <section class="table-responsive" id="printable">

                <table class="moon table table-striped table-hover h-150px">
                    <thead>
                        <tr style="height: 2px">
                            <th>#</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-primary">
                            <th>{{ $order->id }}</th>
                            <td class="width-16-rem text-center">
                                <a href="" class="btn btn-dark btn-sm text-white width-6-rem mi" id="print">
                                    <i class="fa fa-print"></i> Print
                                </a>
                                <a href="{{ route('admin.market.order.show.detail', $order->id) }}"
                                    class="btn btn-warning btn-sm width-6-rem mi">
                                    <i class="fa fa-book"></i> Details
                                </a>
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Customer name :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->user->fullName ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Province :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->province->name ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>City :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->city->name ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Address :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->address ?? '-' }}
                            </td>
                        </tr>



                        <tr class="border-bottom">
                            <th>Postal code :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->postal_code ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>No :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->no ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Unit :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->unit ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Recipient name:</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->recipient_name ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Mobile :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->address->mobile ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Payment type :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->payment_type }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Order status :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->payment_status }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Delivery amount :</th>
                            <td class="text-left font-weight-bolder">
                                {{ number_format($order->delivery_amount, 2) ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Delivery status :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->delivery_status }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Delivery date :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->delivery_date }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Total order amount (without discount + delivery cost) :</th>
                            <td class="text-left font-weight-bolder">
                                {{ number_format($order->order_final_amount + ($order->order_discount_amount ?? 0), 2) }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Total of all discounts :</th>
                            <td class="text-left font-weight-bolder">
                                {{ number_format($order->order_discount_amount, 2) ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Discount amount of all products :</th>
                            <td class="text-left font-weight-bolder">
                                {{ number_format($order->order_total_products_discount_amount, 2) ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Payable amount :</th>
                            <td class="text-left font-weight-bolder">
                                {{ number_format($order->order_final_amount, 2) }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Bank :</th>
                            <td class="text-left font-weight-bolder">
                                {{ optional($order->payments->where('status', 'paid')->last())->gateway ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Coupon used :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->coupon->code ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>The discount amount of the coupon code :</th>
                            <td class="text-left font-weight-bolder">
                                {{ number_format($order->order_coupon_discount_amount, 2) ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Common discount used: :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->commonDiscount->title ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Common discount amount :</th>
                            <td class="text-left font-weight-bolder">
                                {{ number_format($order->order_common_discount_amount, 2) ?? '-' }}
                            </td>
                        </tr>

                        <tr class="border-bottom">
                            <th>Order status :</th>
                            <td class="text-left font-weight-bolder">
                                {{ $order->order_status_value }}
                            </td>
                        </tr>

                    </tbody>

                </table>
            </section>
        </section>
    </section>
    </section>
@endsection

@section('script')
    {{-- عملیات مربوط به چاپ کردن --}}
    <script>
        var printBtn = document.getElementById('print');
        printBtn.addEventListener('click', function() {
            printContent('printable');
        })

        function printContent(el) {
            var restorePage = $('body').html();
            var printContent = $('#' + el).clone();
            $('body').empty().html(printContent);
            window.print();
            $('body').html(restorePage);
        }
    </script>
@endsection
