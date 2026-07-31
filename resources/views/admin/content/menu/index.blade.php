@extends('admin.layouts.master2')

@section('head-tag')
    <title>Menus</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">content</a></li>
                <li class="breadcrumb-item active">menus</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Menus</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                @can('create-menu')
                    <a href="{{ route('admin.content.menu.create') }}" class="btn btn-dark btn-sm my-btn ">Create new
                        menu</a>
                @endcan

            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Parent Menu</th>
                            <th scope="col">url</th>
                            <th scope="col">Status</th>
                            @canany(['update-menu', 'delete-menu'])
                                <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                            @endcanany
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($menus as $menu)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->parent->name ?? 'Main menu' }}</td>
                                <td>{{ $menu->url }}</td>
                                <td>
                                    <label>
                                        <input id="{{ $menu->id }}" onchange="changeStatus({{ $menu->id }})"
                                            @cannot('update-menu') disabled @endcannot
                                            data-url="{{ route('admin.content.menu.status', $menu->id) }}" type="checkbox"
                                            @if ($menu->status === 1) checked @endif>
                                    </label>
                                </td>

                                @canany(['update-menu', 'delete-menu'])
                                    <td class="width-16-rem text-center">
                                        @can('update-menu')
                                            <a href="{{ route('admin.content.menu.edit', $menu->id) }}"
                                                class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                                Edit</a>
                                        @endcan

                                        @can('delete-menu')
                                            <form class="d-inline" action="{{ route('admin.content.menu.destroy', $menu->id) }}"
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
    <script type="text/javascript">
        // status
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
                            successToast('Menu successfully activated.');
                        } else {
                            element.prop('checked', false);
                            successToast('Menu successfully disabled.');
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
