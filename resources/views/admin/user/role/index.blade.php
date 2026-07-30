@extends('admin.layouts.master2')

@section('head-tag')
    <title>Roles</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">User</a></li>
                <li class="breadcrumb-item active">Roles</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Roles</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    {{-- // search --}}
                    <form class="d-flex align-items-center" action="{{ route('admin.user.role.index') }}" method="GET">

                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" style="margin-right: 5px" placeholder="search..">

                        <button type="submit" class="btn btn-sm btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>

                    </form>
                </div>
                <a href="{{ route('admin.user.role.create') }}" class="btn btn-dark btn-sm my-btn ">Create new
                    role</a>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Status</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Action</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($roles as $role)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $role->name }}</td>

                                <td>{{ $role->description }}</td>
                                <td>
                                    @if ($role->permissions->isEmpty())
                                        <span class="text-danger">No permissions are defined for this role.</span>
                                    @else
                                        @foreach ($role->permissions->take(5) as $index => $permission)
                                            {{ $index + 1 }} - {{ $permission->name }}<br>
                                        @endforeach
                                        @if ($role->permissions->count() > 5)
                                            <span class="text-muted">…</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <label>
                                        <input id="{{ $role->id }}" onchange="changeStatus({{ $role->id }})"
                                            data-url="{{ route('admin.user.role.status', $role->id) }}" type="checkbox"
                                            @if ($role->status === 1) checked @endif>
                                    </label>
                                </td>
                                <td class="width-17-rem text-center">
                                    <a href="{{ route('admin.user.role.permission-form', $role->id) }}"
                                        class="btn btn-success btn-sm width-5-rem mi"><i class="fa fa-shield-alt"></i>
                                        Access</a>
                                    <a href="{{ route('admin.user.role.edit', $role->id) }}"
                                        class="btn btn-primary btn-sm width-5-rem mi"><i class="fa fa-edit"></i>
                                        Edit</a>
                                    <form class="d-inline" action="{{ route('admin.user.role.destroy', $role->id) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm width-5-rem mi delete"
                                            type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $roles->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            </section>
        </section>

    </section>
@endsection

@section('script')
    <script type="text/javascript">
        // activation
        function changeStatus(id) {
            var element = $("#" + id)
            var url = element.attr('data-url')
            var elementValue = !element.prop('checked');
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        if (response.checked) {
                            element.prop('checked', true);
                            successToast('Role successfully activated.');
                        } else {
                            element.prop('checked', false);
                            successToast('Role successfully disabled.');
                        }
                    } else {
                        element.prop('checked', elementValue);
                        errorToast('There was a problem while editing.');
                    }
                },
                error: function() {
                    element.prop('checked', elementValue);
                    errorToast('Connection not established.');
                }
            });

            function successToast(message) {
                var successToastTag =
                    '<section class="toast" data-delay="5000">\n' +
                    '<section class="toast-body py-2 d-flex toast-success">\n' +
                    '<p class="ml-auto my-1">' + message + '</p>\n' +
                    '<button type="button" class="mr-2 text-white mb-0 close" data-dismiss="toast" aria-label="Close">\n' +
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close">\n' +
                    '</section>\n' +
                    '</section>';
                $('.toast-wrapper').append(successToastTag);
                $('.toast').toast('show').delay(5500).queue(function() {
                    $(this).remove();
                });
            }

            function errorToast(message) {
                var errorToastTag = ' <section class="toast" data-delay="5000">\n' +
                    '<section class="toast-body py-3 d-flex bg-danger text-white">\n' +
                    '<p class="ml-auto my-1">' + message + '</p>\n' +
                    '<button type="button" class="mr-2 text-white mb-0 close" data-dismiss="toast" aria-label="Close">\n' +
                    '<span aria-hidden="true">&times;</span>\n' +
                    '</button>\n' +
                    '</section>\n' +
                    '</section>';
                $('.toast-wrapper').append(errorToastTag);
                $('.toast').toast('show').delay(5500).queue(function() {
                    $(this).remove();
                });
            }
        }
    </script>
    @include('admin.alerts.sweetalert.delete-confirm', ['className' => 'delete'])
@endsection
