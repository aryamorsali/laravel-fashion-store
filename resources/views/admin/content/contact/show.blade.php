@extends('admin.layouts.master2')

@section('head-tag')
    <title>Show Contact Message</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Content</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Contact</a></li>
                <li class="breadcrumb-item active">Show Contact Message</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Show Contact Message</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.content.contact.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section class="card mb-3">
                <section class="card-header text-white bg-success">
                    {{ $contact->user->id ?? '-' }} -
                    {{ $contact->user->fullName ?? 'User deleted' }} <br>
                    {{ $contact->email ?? '-' }}
                </section>
                <section class="card-body">
                    <p class="card-text mt-4">{{ $contact->body }}</p>
                </section>

            </section>
        </section>
    </section>
@endsection
