@extends('admin.layouts.master2')

@section('head-tag')
    <title>SMS notification</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Notification</a></li>
                <li class="breadcrumb-item active">SMS notification</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">SMS notification</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    {{-- // search --}}
                    <form class="d-flex align-items-center" action="{{ route('admin.notification.sms.index') }}"
                        method="GET">

                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" style="margin-right: 5px" placeholder="search..">

                        <button type="submit" class="btn btn-sm btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>
                    </form>
                </div>
                @can('create-sms-notification')
                    <a href="{{ route('admin.notification.sms.create') }}" class="btn btn-dark btn-sm my-btn ">Create SMS
                        notification</a>
                @endcan

            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">SMS text</th>
                            <th scope="col">Date of posting</th>
                            <th scope="col">Status</th>
                            @canany(['update-sms-notification', 'delete-sms-notification', 'send-sms-notification'])
                                <th class=" text-right"><i class="fa fa-cogs"></i> Action</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($sms as $singel_sms)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ Str::limit($singel_sms->title, 20) ?? '-' }}</td>
                                <td>{{ Str::limit($singel_sms->body, 20) }}</td>

                                <td>{{ $singel_sms->published_at ?? '-' }}</td>
                                <td>{{ $singel_sms->status }}</td>

                                @canany(['update-sms-notification', 'delete-sms-notification', 'send-sms-notification'])
                                    <td class="width-16-rem text-center">

                                        @can('update-sms-notification')
                                            <a href="{{ route('admin.notification.sms.edit', $singel_sms->id) }}"
                                                class="btn btn-primary btn-sm width-5-rem mi"><i class="fa fa-edit"></i>
                                                Edit</a>
                                        @endcan


                                        @can('delete-sms-notification')
                                            <form class="d-inline"
                                                action="{{ route('admin.notification.sms.destroy', $singel_sms->id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger btn-sm width-4-rem mi delete"
                                                    type="submit">Delete</button>
                                            </form>
                                        @endcan


                                        @can('send-sms-notification')
                                            <a href="{{ route('admin.notification.sms.send', $singel_sms->id) }}"
                                                class="btn btn-success btn-sm width-5-rem mi">
                                                Send</a>
                                        @endcan

                                    </td>
                                @endcanany

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </section>
            <div class="d-flex justify-content-center mt-4">
                {{ $sms->onEachSide(1)->links('vendor.pagination.custom') }}
            </div>
        </section>

    </section>
@endsection

@section('script')
    @include('admin.alerts.sweetalert.delete-confirm', ['className' => 'delete'])
@endsection
