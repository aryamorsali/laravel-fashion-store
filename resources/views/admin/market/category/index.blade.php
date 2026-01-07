@extends('admin.layouts.master2')

@section('head-tag')
    <title>Product Categories</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">market</a></li>
                <li class="breadcrumb-item active">category</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Product Categories</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <a href="{{ route('admin.market.category.create') }}" class="btn btn-dark btn-sm my-btn ">Create new
                    category</a>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category name</th>
                            <th scope="col">Parent</th>
                            <th scope="col">Image</th>
                            <th scope="col">Tag</th>
                            <th scope="col">Status</th>
                            <th scope="col">In Menu</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($productCategories as $productCategory)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $productCategory->name }}</td>
                                <td>{{ $productCategory->parent->name ?? 'Main category' }}</td>
                                <td>
                                    @if (!empty($productCategory->image) && isset($productCategory->image['indexArray'][$productCategory->image['currentImage']]))
                                        <img class="rounded"
                                            src="{{ asset($productCategory->image['indexArray'][$productCategory->image['currentImage']]) }}"
                                            alt="" width="75" height="65">
                                    @else
                                    <p class="text-danger mt-3">without image</p>
                                    @endif

                                </td>
                                <td>{{ Str::limit($productCategory->tags, 40) }}</td>
                                <td>
                                    <label>
                                        <input id="{{ $productCategory->id }}"
                                            onchange="changeStatus({{ $productCategory->id }})"
                                            data-url="{{ route('admin.market.category.status', $productCategory->id) }}"
                                            type="checkbox" @if ($productCategory->status === 1) checked @endif>
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input id="{{ $productCategory->id }}-show_in_menu"
                                            onchange="show_in_menu({{ $productCategory->id }})"
                                            data-url="{{ route('admin.market.category.show-in-menu', $productCategory->id) }}"
                                            type="checkbox" @if ($productCategory->show_in_menu === 1) checked @endif>
                                    </label>
                                </td>
                                <td class="width-16-rem text-center">
                                    <a href="{{ route('admin.market.category.edit', $productCategory->id) }}"
                                        class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                        Edit</a>
                                    <form class="d-inline"
                                        action="{{ route('admin.market.category.destroy', $productCategory->id) }}"
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
                            successToast('Category successfully activated.');
                        } else {
                            element.prop('checked', false);
                            successToast('Category successfully disabled.');
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

        // showInMenu
        function show_in_menu(id) {
            var element = $("#" + id + '-show_in_menu')
            var url = element.attr('data-url')
            var elementValue = !element.prop('checked');
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    if (response.show_in_menu) {
                        if (response.checked) {
                            element.prop('checked', true);
                            successToast('Category successfully enabled in menu.');
                        } else {
                            element.prop('checked', false);
                            successToast('Category successfully disabled in menu.');
                        }
                    } else {
                        element.prop('checked', elementValue);
                        errorToast('There was a problem while editing');
                    }
                },
                error: function() {
                    element.prop('checked', elementValue);
                    errorToast('Connection not established');
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
