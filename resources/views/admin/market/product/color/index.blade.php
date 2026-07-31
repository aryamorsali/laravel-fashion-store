@extends('admin.layouts.master2')

@section('head-tag')
    <title>Colors</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">market</a></li>
                <li class="breadcrumb-item active">colors</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Colors</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                @can('create-color')
                    <section class="d-flex justify-content-between align-items-center mt-3 mb-3 pb-2">
                        <a href="{{ route('admin.market.color.create') }}" class="btn btn-dark btn-sm mx-1 my_btn">Create
                            new color</a>
                    </section>
                @endcan
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Color Name</th>
                            <th scope="col">Color</th>
                            <th scope="col">Hex Code</th>
                            @canany(['delete-color'])
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($colors as $color)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $color->name }}</td>
                                <td>
                                    <div
                                        style="width: 30px; height: 30px; border-radius: 5px; background-color: {{ $color->hex_code }};  display: inline-block;">
                                    </div>
                                </td>
                                <td>{{ $color->hex_code }}</td>

                                @can('delete-color')
                                    <td class="width-16-rem text-center">
                                        <form class="d-inline" action="{{ route('admin.market.color.destroy', $color) }}"
                                            method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger btn-sm width-6-rem mi delete" type="submit"><i
                                                    class="fa fa-trash-alt"></i> Delete</button>
                                        </form>
                                    </td>
                                @endcan

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
