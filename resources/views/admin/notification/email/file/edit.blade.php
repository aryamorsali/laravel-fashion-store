@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Email notification file</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Notitfication</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Email notification</a></li>
                <li class="breadcrumb-item active">Edit Email notification file</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit email notification file</h3>
            </section>
            @include('admin.alerts.alert-section.error')
            <section class="d-flex align-items-center mt-4 mb-3 border-bottom pb-2">
                <a href="{{ route('admin.notification.email.file.index', $email) }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.notification.email.file.update', ['email' => $email, 'file' => $file->id]) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <section class="row">

                        {{-- Current file info --}}
                        <section class="col-12 my-3">
                            <p class="mb-1"><strong>Size:</strong> {{ number_format($file->file_size / 1024, 1) }} KB</p>

                            @php
                                $mime = $file->mime_type;
                                $isImage = in_array($mime, ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp

                            @if ($isImage)
                                <img src="{{ asset($file->file_path) }}" alt="file preview" style="max-width: 200px"
                                    class="my-2">
                            @elseif ($mime === 'pdf')
                                <a href="{{ asset($file->file_path) }}" target="_blank" class="btn btn-danger btn-sm my-2">
                                    View PDF
                                </a>
                            @elseif ($mime === 'zip')
                                <a href="{{ asset($file->file_path) }}" download class="btn btn-secondary btn-sm my-2">
                                    Download ZIP
                                </a>
                            @elseif (in_array($mime, ['doc', 'docx']))
                                <a href="{{ asset($file->file_path) }}" download class="btn btn-primary btn-sm my-2">
                                    Download Word File
                                </a>
                            @endif
                        </section>

                        {{-- Upload new file --}}
                        <section class="col-12 my-3">
                            <label for="file">Replace with new file (optional)</label>
                            <input type="file" class="form-control form-control-sm" name="file" id="file">
                            @error('file')
                                <div class="text-danger mt-1" style="font-size: 12px;">
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
