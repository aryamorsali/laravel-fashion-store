@extends('admin.layouts.master2')

@section('head-tag')
    <title>Amazing Sales</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Amazing Sales</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Amazing Sales</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                @can('create-amazing-sale')
                    <a href="{{ route('admin.market.discount.amazingSale.create') }}" class="btn btn-dark btn-sm my-btn ">Add
                        Product to Amazing Sale List</a>
                @endcan

            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product</th>
                            <th scope="col">Discount percentage</th>
                            <th scope="col">Start date</th>
                            <th scope="col">End date</th>
                            <th scope="col">Status</th>
                            @canany(['update-amazing-sale', 'delete-amazing-sale'])
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Action</th>
                            @endcanany

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($amazingSales as $amazingSale)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <strong>{{ $amazingSale->productVariant?->product?->name }}</strong>
                                    <div class="small text-muted">
                                        Color: {{ $amazingSale->productVariant?->color?->name ?? '—' }},
                                        Size:
                                        @if ($amazingSale->productVariant?->size)
                                            {{ $amazingSale->productVariant->size->name }}
                                        @else
                                            <span class="text-danger">no size</span>
                                        @endif
                                        ,
                                        Price:
                                        ${{ rtrim(rtrim(number_format($amazingSale->productVariant?->price, 2), '0'), '.') }},
                                        Stock: {{ $amazingSale->productVariant?->availableStock() ?? '—' }},
                                    </div>
                                </td>

                                <td>{{ $amazingSale->percentage }}%</td>
                                <td>{{ $amazingSale->start_date }}</td>
                                <td>{{ $amazingSale->end_date }}</td>
                                <td>
                                    @switch($amazingSale->is_active)
                                        @case(0)
                                            <span class="text-danger">inactive</span>
                                        @break

                                        @case(1)
                                            <span class="text-success">active</span>
                                        @break
                                    @endswitch
                                </td>

                                @canany(['update-amazing-sale', 'delete-amazing-sale'])
                                    <td class="width-16-rem text-center">
                                        @can('update-amazing-sale')
                                            <a href="{{ route('admin.market.discount.amazingSale.edit', $amazingSale->id) }}"
                                                class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                                Edit</a>
                                        @endcan
                                        @can('delete-amazing-sale')
                                            <form class="d-inline"
                                                action="{{ route('admin.market.discount.amazingSale.destroy', $amazingSale->id) }}"
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
