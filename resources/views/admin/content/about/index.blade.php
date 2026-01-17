@extends('admin.layouts.master2')

@section('head-tag')
    <title>About</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Content</a></li>
                <li class="breadcrumb-item active">About</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2 mb-4">About</h3>
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
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Image</th>
                            <th class="max-width-16-rem text-center"><i class="fa fa-cogs"></i> Setting</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <th scope="row">1</th>
                            <td>{{ $about->title }}</td>

                            <td>{!! Str::limit($about->description, 40) !!}</td>

                            <td>
                                @if ($about->image)
                                 <img class="rounded" src="{{ asset($about->image) }}" alt="{{$about->title}}" width="90"
                                    height="70">
                                    @else
                                    <span style="color: red">No image</span>   
                                @endif
                                
                            </td>

                            <td class="width-16-rem text-center">
                                <a href="{{ route('admin.content.about.edit', $about->id) }}"
                                    class="btn btn-primary btn-sm width-8-rem mi"><i class="fa fa-edit"></i>
                                    Edit</a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </section>
        </section>

    </section>
@endsection

