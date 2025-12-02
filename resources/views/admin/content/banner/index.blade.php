@extends('admin.layouts.master2')

@section('head-tag')
    <title>Banners</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Content</a></li>
                <li class="breadcrumb-item active">Banners</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Banners</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
                <a href="{{ route('admin.content.banner.create') }}" class="btn btn-dark btn-sm my-btn ">Create new
                    banner</a>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Subtitle</th>
                            <th scope="col">Button text</th>
                            <th scope="col">Button url</th>
                            <th scope="col">Image</th>
                            <th scope="col">Status</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($banners as $banner)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ Str::limit($banner->title, 20)  ?? '-'}}</td>
                                <td>{{ Str::limit($banner->subtitle, 20)  ?? '-'}}</td>
                                <td>{{ $banner->button_text  ?? '-'}}</td>
                                <td>{{ $banner->button_url ?? '-' }}</td>
                                <td>
                                    <img class="rounded"
                                        src="{{ asset($banner->image) }}"
                                        alt="" width="90" height="70">
                                </td>
                                <td>
                                    <label>
                                        <input id="{{ $banner->id }}" onchange="changeStatus({{ $banner->id }})"
                                            data-url="{{ route('admin.content.banner.status', $banner) }}" type="checkbox"
                                            @if ($banner->status === 1) checked @endif>
                                    </label>
                                </td>
                                <td class="width-16-rem text-center">
                                    <a href="{{ route('admin.content.banner.edit', $banner->id) }}"
                                        class="btn btn-primary btn-sm width-6-rem mi"><i class="fa fa-edit"></i>
                                        Edit</a>
                                    <form class="d-inline"
                                        action="{{ route('admin.content.banner.destroy', $banner->id) }}" method="post">
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
                            successToast('banner successfully activated.');
                        } else {
                            element.prop('checked', false);
                            successToast('banner successfully disabled.');
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
