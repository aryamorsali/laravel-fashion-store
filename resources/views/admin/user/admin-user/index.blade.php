@extends('admin.layouts.master2')

@section('head-tag')
    <title>Admins</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Users</a></li>
                <li class="breadcrumb-item active">Admins</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Admins</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    {{-- // search --}}
                    <form class="d-flex align-items-center" action="{{ route('admin.user.admin.index') }}" method="GET">

                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" style="margin-right: 5px" placeholder="search..">

                        <button type="submit" class="btn btn-sm btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>

                    </form>

                </div>
                <a href="{{ route('admin.user.admin.add') }}" class="btn btn-primary btn-sm" style="margin-right: 4px">Add
                    admin</a>

                <a href="{{ route('admin.user.admin.create') }}" class="btn btn-dark btn-sm">Create new admin</a>

            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">User</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Email</th>
                            <th scope="col">Roles</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Status</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($admins as $admin)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $admin->full_name }}</td>
                                <td>{{ $admin->mobile }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>
                                    @if ($admin->is_owner)
                                        <span class="text-success">Site owner</span>
                                    @elseif($admin->roles->isEmpty())
                                        <span class="text-danger">No role found.</span>
                                    @else
                                        @foreach ($admin->roles as $index => $role)
                                            {{ $index + 1 }} - {{ $role->name }}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $permissions = collect();

                                        foreach ($admin->roles as $role) {
                                            $permissions = $permissions->merge($role->permissions);
                                        }

                                        $permissions = $permissions->merge($admin->permissions)->unique('id');
                                    @endphp

                                    @if ($admin->is_owner)
                                        <span class="text-success">Full discretion</span>
                                    @elseif($permissions->isEmpty())
                                        <span class="text-danger">No permissions found.</span>
                                    @else
                                        @foreach ($permissions->take(5) as $index => $permission)
                                            {{ $index + 1 }} - {{ $permission->name }}<br>
                                        @endforeach
                                        @if ($permissions->count() > 5)
                                            <span class="text-muted">…</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($admin->is_owner)
                                        @switch($admin->activation)
                                            @case(1)
                                                <span class="text-success">Active</span>
                                            @break

                                            @case(0)
                                                <span class="text-danger">Disabled</span>
                                            @break

                                            @default
                                        @endswitch
                                    @else
                                        <label>
                                            <input id="{{ $admin->id }}" onchange="changeStatus({{ $admin->id }})"
                                                data-url="{{ route('admin.user.admin.activation', $admin->id) }}"
                                                type="checkbox" @if ($admin->activation === 1) checked @endif>
                                        </label>
                                    @endif
                                </td>

                                <td class="width-14-rem text-center">
                                    @if ($admin->is_owner)
                                        -
                                    @else
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-success btn-sm btn-block dropdown-toggle"
                                                role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fa fa-tools"></i> Operation
                                            </a>

                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <li>
                                                    <a href="{{ route('admin.user.admin.edit', $admin->id) }}"
                                                        class="dropdown-item text-right"><i class="fa fa-edit"></i>
                                                        Edit</a>
                                                </li>
                                                <li>
                                                    <form class="d-inline"
                                                        action="{{ route('admin.user.admin.destroy', $admin->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="dropdown-item text-right delete" type="submit"><i
                                                                class="fa fa-trash-alt"></i> Delete</button>
                                                    </form>
                                                </li>

                                                <li>
                                                    <a href="{{ route('admin.user.admin.role', $admin) }}"
                                                        class="dropdown-item text-right">
                                                        <i class="fa-solid fa-user-tag"></i> Roles
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.user.admin.permission', $admin) }}"
                                                        class="dropdown-item text-right">
                                                        <i class="fa-solid fa-key"></i> Permissions
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.user.admin.add.permission', $admin) }}"
                                                        class="dropdown-item text-right">
                                                        <i class="fa-solid fa-user-shield"></i> Manage Permission
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.user.admin.revokeAdmin', $admin) }}"
                                                        class="dropdown-item text-right">
                                                        <i class="fa-solid fa-user-slash"></i> Revoke Admin
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $admins->onEachSide(1)->links('vendor.pagination.custom') }}
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
                    if (response.activation) {
                        if (response.checked) {
                            element.prop('checked', true);
                            successToast('Admin successfully activated.');
                        } else {
                            element.prop('checked', false);
                            successToast('Admin successfully disabled.');
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
