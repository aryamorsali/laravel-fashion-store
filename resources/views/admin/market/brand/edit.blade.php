@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Brand</title>

    <style>
        .select2-selection__rendered {
            font-family: "Roboto", "Helvetica Neue", Arial, sans-serif;
            color: #000000;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
        }

        .select2-selection {
            border: none;
            border-radius: 6px;
        }

        .select2-results__option {
            color: #000000;
            padding: 8px 12px;
            font-size: 13px;
        }

        .select2-container .select2-search--inline .select2-search__field {
            font-family: "Roboto", "Helvetica Neue", Arial, sans-serif;
        }

        .select2-container--open .select2-selection--single,
        .select2-container--open .select2-selection--multiple {
            border-color: #389af7 !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e9eef5;
            border: 1px solid #e9eef5;
            margin-top: 8px;
            font-weight: 500;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #000000;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">content</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">brands</a></li>
                <li class="breadcrumb-item active">edit brand</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Brand</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.brand.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.brand.update', $brand) }}" method="post" enctype="multipart/form-data"
                    id="form">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name"
                                    value="{{ old('name', $brand->name) }}">
                            </div>
                            @error('name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="logo">Logo</label>
                                <input type="file" class="form-control form-control-sm" name="logo" id="logo">
                            </div>
                            @error('logo')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>
                        @if ($brand->image)
                            <section class="row my-2">
                                @php
                                    $number = 2;
                                @endphp
                                @foreach ($brand->image['indexArray'] as $key => $value)
                                    <section class="col-md-{{ 6 / $number }} mr-5">
                                        <div class="form-check  p-1">
                                            <input type="radio" name="currentImage" class="form-check-input"
                                                value="{{ $key }}" id="{{ $number }}"
                                                @if ($brand->image['currentImage'] == $key) checked @endif>
                                            <label for="{{ $number }}" class="form-check-label mx-3">
                                                <img src="{{ asset($value) }}" class="img-fluid rounded w-100"
                                                    alt="">
                                            </label>
                                        </div>
                                    </section>
                                    @php
                                        $number++;
                                    @endphp
                                @endforeach
                            </section>
                        @endif

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label>Tags</label>
                                <select class="select2 form-control form-control-sm" id="select_tags" multiple
                                    name="tags[]">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            @if (in_array($tag->id, old('tags', $brand->tags->pluck('id')->toArray()))) selected @endif>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('tags')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control form-control-sm" id="status">
                                    <option value="0" @if (old('status', $brand->status) == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('status', $brand->status) == 1) selected @endif>active
                                    </option>
                                </select>
                            </div>
                            @error('status')
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
    @section('script')
         {{-- select 2 --}}
        <script>
            var select_tags = $('#select_tags');
            select_tags.select2({
                placeholder: 'Please enter tags (optional)',
                multiple: true,
            })
        </script>
    @endsection
