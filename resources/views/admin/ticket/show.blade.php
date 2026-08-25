@extends('admin.layouts.master2')

@section('head-tag')
    <title>Show Tickets</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Tickets</a></li>
                <li class="breadcrumb-item active">Show Tickets</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Show Tickets</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.ticket.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section class="card my-3">
                <section class="card-header text-white bg-primary">
                    {{ optional($ticket->user)->fullName ?? 'User deleted' }} - {{ optional($ticket->user)->id ?? '-' }}
                </section>
                <section class="card-body">
                    <h5 class="card-title my-2">
                        Subject : {{ $ticket->subject }}
                    </h5>
                    <p class="card-text mt-4">{{ $ticket->description }}</p>

                </section>

            </section>

            {{-- <hr class="my-4"> --}}
            @if ($ticket->children->count() > 0)
                <div class="border my-2">
                    @foreach ($ticket->children as $child)
                        <section class="card m-4 my-3">
                            <section class="card-header bg-light d-flex justify-content-between">
                                <div>
                                    {{ optional($ticket->user)->fullName ?? 'کاربر حذف‌شده' }}
                                    - پاسخ دهنده : {{ optional($child->user)->fullName ?? 'نامشخص' }}
                                </div>

                                <small>{{ $child->created_at }}</small>
                            </section>
                            <section class="card-body">
                                <p class="card-text">{{ $child->description }}</p>
                            </section>
                        </section>
                    @endforeach
                </div>
            @endif
            <section>
                <form action="{{ route('admin.ticket.answer', $ticket->id) }}" method="post">
                    @csrf
                    <section class="row">
                        <section class="col-12">
                            <div class="form-group">
                                <label for="description">Answer</label>
                                <textarea name="description" id="description" cols="30" rows="4" class="form-control form-control-sm "></textarea>
                            </div>
                            @error('description')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>
                        <section class="col-12 my-3 d-flex justify-content-end">
                            <button class="btn btn-primary">Submit</button>
                        </section>
                    </section>
                </form>
            </section>
        </section>


    @endsection
