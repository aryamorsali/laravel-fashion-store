@extends('admin.layouts.master2')

@section('head-tag')
    <title>Show comments</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">content</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">comments</a></li>
                <li class="breadcrumb-item active">show comment</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Show comments</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.content.comment.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section class="card mb-3">
                <section class="card-header text-white bg-success">
                    {{ $comment->user->fullName ?? 'User deleted' }} - {{ $comment->user->id ?? '-' }}
                </section>
                <section class="card-body">
                    <h5 class="card-title my-2">
                        Post details : {{ $comment->commentable->title }} - Post code : {{ $comment->commentable->id }}
                    </h5>
                    <p class="card-text mt-4">{{ $comment->body }}</p>

                </section>

            </section>

            <hr>
            @if ($comment->children->count() > 0)
                <div class=" my-2">
                    @foreach ($comment->children as $child)
                        <section class="card m-4 my-3">
                            <section class="card-header bg-secondary text-white d-flex justify-content-between">
                                <div>Respondent : {{ $child->user ? $child->user->fullName : 'نامشخص' }}</div >
                                <small>{{ $child->created_at }}</small>
                            </section>
                            <section class="card-body">
                                <p class="card-text">{{ $child->body }}</p>
                            </section>
                        </section>
                    @endforeach
                </div>
            @endif

            @if ($comment->parent_id == null)
                <section>
                    <form action="{{ route('admin.content.comment.answer', $comment->id) }}" method="post">
                        @csrf
                        <section class="row">
                            <section class="col-12 my-2">
                                <div class="form-group">
                                    <label for="body">Admin's response</label>
                                    <textarea name="body" id="body" rows="4" class="form-control form-control-sm ">{{ old('body') }}</textarea>
                                </div>
                                @error('body')
                                    <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </section>
                            <section class="col-12 my-2">
                                <button class="btn btn-primary btn-sm">Submit</button>
                            </section>
                        </section>
                    </form>
                </section>
            @endif
        </section>
    @endsection
