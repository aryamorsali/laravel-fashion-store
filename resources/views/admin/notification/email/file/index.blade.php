@extends('admin.layouts.master2')

@section('head-tag')
    <title>Email notification file</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Notification</a></li>
                <li class="breadcrumb-item active">Email notification file</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Email notification file</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <a href="{{ route('admin.notification.email.index') }}" class="btn btn-dark btn-sm my-btn me-2">
                    Cancle</a>
                <a href="{{ route('admin.notification.email.file.create', $email) }}" class="btn btn-info btn-sm my-btn ">Create
                    email notification file</a>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Email subject</th>
                            <th scope="col">File size</th>
                            <th scope="col">File type</th>
                            <th class="text-right"><i class="fa fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($email->files as $file)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ Str::limit($email->subject, 20) ?? '-' }}</td>
                                <td>{{ $file->file_size }}</td>
                                <td>{{ $file->mime_type }}</td>

                                <td class="width-16-rem text-right">
                                    <a href="{{ route('admin.notification.email.file.edit', ['email' => $email, 'file' => $file->id]) }}"
                                        class="btn btn-primary btn-sm width-4-rem mi">
                                        Edit</a>
                                    <form class="d-inline"
                                        action="{{ route('admin.notification.email.file.destroy', ['email' => $email, 'file' => $file->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm width-4-rem mi delete"
                                            type="submit">Delete</button>
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
