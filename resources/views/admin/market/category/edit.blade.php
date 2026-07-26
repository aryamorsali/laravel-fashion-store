@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Category</title>

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

   <style>
        .image-option {
            cursor: pointer;
            transition: all 0.2s ease;
            border: 2px solid transparent;
            border-radius: 8px;
        }

        input[type="radio"] {
            display: none;
        }

        input[type="radio"]:checked+label img {
            border: 3px solid #3586fe;
            transform: scale(1.05);
            box-shadow: 0 0 12px rgba(13, 110, 253, 0.5);
        }
    </style>

@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">category</a></li>
                <li class="breadcrumb-item active">edit category</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Category</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.category.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.category.update', $productCategory) }}" method="post"
                    enctype="multipart/form-data" id="form">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name"
                                    value="{{ old('name', $productCategory->name) }}">
                            </div>
                            @error('name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="parent_id">Parent</label>
                                <select name="parent_id" class="form-control form-control-sm" id="parent_id">
                                    <option value="">Main category</option>
                                    @foreach ($parent_categories as $parent_category)
                                        <option value="{{ $parent_category->id }}"
                                            @if (old('parent_id', $productCategory->parent_id) == $parent_category->id) selected @endif>
                                            {{ $parent_category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('parent_id')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea name="description" class="form-control form-control-sm" id="description">{{ old('description', $productCategory->description) }}</textarea>
                            </div>
                            @error('description')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control form-control-sm" name="image" id="image">
                            </div>
                            @error('image')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        @if ($productCategory->image)
                            <section class="row mt-4 my-2">
                                @foreach ($productCategory->image['indexArray'] as $key => $value)
                                    <section class="col-md-3 col-sm-6 mb-3">
                                        <input type="radio" name="currentImage" id="image-{{ $key }}"
                                            value="{{ $key }}" @checked($productCategory->image['currentImage'] == $key)>
                                        <label for="image-{{ $key }}" class="d-block text-center">
                                            <img src="{{ asset($value) }}" class="img-fluid rounded image-option"
                                                alt="">
                                        </label>
                                    </section>
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
                                            @if (in_array($tag->id, old('tags', $productCategory->tags->pluck('id')->toArray()))) selected @endif>
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
                                    <option value="0" @if (old('status', $productCategory->status) == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('status', $productCategory->status) == 1) selected @endif>active
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
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </section>

                    </section>
                </form>
            </section>
        </section>
    @endsection
    @section('script')
        <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
        </script>

       {{-- select 2 --}}
        <script>
            var select_tags = $('#select_tags');
            select_tags.select2({
                placeholder: 'Please enter tags (optional)',
                multiple: true,
            })
        </script>
    @endsection
