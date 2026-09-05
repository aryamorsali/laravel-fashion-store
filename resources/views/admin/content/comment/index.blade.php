@extends('admin.layouts.master2')

@section('head-tag')
    <title>Comments</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">content</a></li>
                <li class="breadcrumb-item active">comments</li>
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
                            <th scope="col">Reply to</th>
                            <th scope="col">Comment author</th>
                            <th scope="col">User code</th>
                            <th scope="col">Post</th>
                            <th scope="col">Post code</th>
                            <th scope="col">Approval status</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($comments as $comment)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ Str::limit($comment->body, 25) }}</td>
                                <td>{{ $comment->parent_id ? Str::limit($comment->parent->body, 10) : '-' }}</td>
                                <td>{{ $comment->user->full_name }}</td>
                                <td>{{ $comment->author_id }}</td>
                                @if ($comment->commentable)
                                    <td>{{ $comment->commentable->title }}</td>
                                @else
                                    <td class="text-danger">Post deleted</td>
                                @endif
                                <td>{{ $comment->commentable_id }}</td>
                                <td>{{ $comment->approved == 1 ? 'confirmed' : 'not confirmed' }}</td>
                                
                                <td class="width-16-rem text-center">
                                    <a href="{{ route('admin.content.comment.show', $comment->id) }}"
                                        class="btn btn-info btn-sm width-6-rem mi"><i class="fa fa-eye"></i>
                                        Show</a>
                                    <a href="{{ route('admin.content.comment.approved', $comment->id) }}"
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

    @include('admin.alerts.sweetalert.delete-confirm', ['className' => 'delete'])
@endsection
