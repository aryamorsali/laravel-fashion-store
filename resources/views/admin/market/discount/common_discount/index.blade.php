@extends('admin.layouts.master2')

@section('head-tag')
    <title>Common Discounts</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Common Discounts</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Common Discounts</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <a href="{{ route('admin.market.discount.common_discount.create') }}"
                    class="btn btn-dark btn-sm my-btn ">Create new
                    common discount</a>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Discount percentage</th>
                            <th scope="col">Minimum purchase</th>
                            <th scope="col">Discount ceiling</th>
                            <th scope="col">Occasion title</th>
                            <th scope="col">Start date</th>
                            <th scope="col">End date</th>
                            <th scope="col">Status</th>
                            <th class="max-width-14-rem text-center"><i class="fa fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($common_discounts as $common_discount)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $common_discount->percentage }}%</td>
                                <td>
                                    @if ($common_discount->minimal_order_amount)
                                        ${{ $common_discount->minimal_order_amount ?? '-' }}
                                    @else-
                                    @endif
                                </td>
                                <td>
                                    @if ($common_discount->discount_ceiling)
                                        ${{ $common_discount->discount_ceiling ?? '-' }}
                                    @else-
                                    @endif
                                </td>
                                <td>{{ $common_discount->title }}</td>
                                <td>{{ $common_discount->start_date }}</td>
                                <td>{{ $common_discount->end_date }}</td>
                                <td>
                                    @switch($common_discount->status)
                                        @case(0)
                                            <span class="text-warning">
                                                inactive
                                            </span>
                                        @break

                                        @case(1)
                                            <span class="text-success">
                                                active

                                            </span>
                                        @break

                                        @case(2)
                                            <span class="text-danger">
                                                expired

                                            </span>
                                        @break
                                    @endswitch
                                </td>


                                <td class="width-14-rem text-center">
                                    <a href="{{ route('admin.market.discount.common_discount.edit', $common_discount->id) }}"
                                        class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                        Edit</a>
                                    <form class="d-inline"
                                        action="{{ route('admin.market.discount.common_discount.destroy', $common_discount->id) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm width-6-rem mi delete" type="submit"><i
                                                class="fa fa-trash-alt"></i> Delete</button>
                                    </form>
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
