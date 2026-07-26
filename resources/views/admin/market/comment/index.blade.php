@extends('admin.layouts.master2')

@section('head-tag')
    <title>Comments</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item active">Comments</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">Comments</h3>
            </section>

            @include('admin.alerts.alert-section.success')
            @include('admin.alerts.alert-section.error')

            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <div class="me-auto" style="max-width: 16rem;">
                    <input type="text" class="form-control form-control-sm form-text" placeholder="search..">
                </div>
            </section>


            <section class="table-responsive">
                <table class="table table-hover table-striped" style="text-align: center">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Comment author</th>
                            <th scope="col">User code</th>
                            <th scope="col">Product</th>
                            <th scope="col">Product code</th>
                            <th scope="col">Approval status</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($comments as $comment)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ Str::limit($comment->body, 25) }}</td>
                                <td>
                                    @if ($comment->rating)
                                        <div class="rating">
                                            <i style="color: #f5a623; font-size: 14px;" class="fa fa-star"></i>
                                            {{ $comment->rating }}
                                        </div>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $comment->user->full_name ?? '-' }}</td>
                                <td>{{ $comment->author_id }}</td>
                                @if ($comment->commentable)
                                    <td>{{ $comment->commentable->name }}</td>
                                @else
                                    <td class="text-danger">Product
                                        deleted
                                    </td>
                                @endif
                                <td>{{ $comment->commentable_id }}</td>
                                <td>
                                    @if ($comment->approved == 1)
                                        <span class="text-success">confirmed</span>
                                    @else
                                        <span class="text-danger">not confirmed</span>
                                    @endif
                                </td>


                                <td class="width-16-rem text-center">
                                    <a href="{{ route('admin.market.comment.show', $comment->id) }}"
                                        class="btn btn-info btn-sm width-6-rem mi"><i class="fa fa-eye"></i>
                                        Show</a>
                                    <a href="{{ route('admin.market.comment.approved', $comment->id) }}"
                                        class="btn btn-{{ $comment->approved == 0 ? 'success' : 'warning' }} btn-sm width-8-rem mi">
                                        <i class="fa fa-{{ $comment->approved == 0 ? 'check' : 'clock' }}"></i>
                                        {{ $comment->approved == 0 ? 'Confirmed' : 'Not approved' }}</a>
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
                            successToast('Comment successfully activated.');
                        } else {
                            element.prop('checked', false);
                            successToast('Comment successfully disabled.');
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
