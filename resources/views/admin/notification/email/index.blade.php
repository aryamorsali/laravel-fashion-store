@extends('admin.layouts.master2')

@section('head-tag')
    <title>Email notification</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Notification</a></li>
                <li class="breadcrumb-item active">Email notification</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Email notification</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <a href="{{ route('admin.notification.email.create') }}" class="btn btn-dark btn-sm my-btn ">Create Email
                    notification</a>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Email text</th>
                            <th scope="col">Date of posting</th>
                            <th scope="col">Status</th>
                            <th class=" text-right"><i class="fa fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($emails as $email)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ Str::limit($email->subject, 20) ?? '-' }}</td>
                                <td>{!! Str::limit($email->body, 20) !!}</td>

                                <td>{{ $email->published_at ?? '-' }}</td>
                                <td>{{ $email->status }}</td>

                                <td class="width-13-rem text-left">
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-success btn-sm btn-block dropdown-toggle"
                                            role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa fa-tools"></i> Operation
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                            <!-- Attached files -->
                                            <li>
                                                <a href="{{ route('admin.notification.email.file.index', $email->id) }}"
                                                    class="dropdown-item text-right">
                                                    <i class="fa fa-paperclip"></i> Attached files
                                                </a>
                                            </li>

                                            <!-- Edit -->
                                            <li>
                                                <a href="{{ route('admin.notification.email.edit', $email->id) }}"
                                                    class="dropdown-item text-right">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </li>

                                            <!-- Delete -->
                                            <li>
                                                <form class="d-inline"
                                                    action="{{ route('admin.notification.email.destroy', $email->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="dropdown-item text-right delete" type="submit">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </li>

                                            <!-- Send now -->
                                            @if ($email->status !== 'sent')
                                                <li>
                                                    <a href="{{ route('admin.notification.email.send', $email->id) }}"
                                                        class="dropdown-item text-right">
                                                        <i class="fa fa-paper-plane"></i> Send now
                                                    </a>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
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
