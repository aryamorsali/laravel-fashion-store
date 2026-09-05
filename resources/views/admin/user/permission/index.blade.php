@extends('admin.layouts.master2')

@section('head-tag')
    <title>Permissions</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">User</a></li>
                <li class="breadcrumb-item active">Permissions</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Permissions</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    {{-- // search --}}
                    <form class="d-flex align-items-center" action="{{ route('admin.user.permission.index') }}"
                        method="GET">

                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" style="margin-right: 5px" placeholder="search..">

                        <button type="submit" class="btn btn-sm btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>

                    </form>

                </div>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($permissions as $permission)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $permission->name }}</td>

                                <td>{{ $permission->description }}</td>
                                <td>
                                    @if ($permission->roles->isEmpty())
                                        <span class="text-danger">No roles are defined for this access level.</span>
                                    @else
                                        @foreach ($permission->roles as $index => $role)
                                            {{ $index + 1 }} - {{ $role->name }}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <label>
                                        <input id="{{ $permission->id }}" onchange="changeStatus({{ $permission->id }})"
                                            data-url="{{ route('admin.user.permission.status', $permission->id) }}"
                                            type="checkbox" @if ($permission->status === 1) checked @endif>
                                    </label>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    {{ $permissions->onEachSide(1)->links('vendor.pagination.custom') }}
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
                            successToast('Permission successfully activated.');
                        } else {
                            element.prop('checked', false);
                            successToast('Permission successfully disabled.');
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
@endsection
