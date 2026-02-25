@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

    <style>
        .select2-selection__rendered {
            font-family: "Roboto", "Helvetica Neue", Arial, sans-serif;
            color: #000000;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 13px;
        }

        .select2-selection {
            border: none;
            border-radius: 6px;
        }

        .select2-results__option {
            color: #000000;
            background-color: #389af7;
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
        .variant-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }

        .variant-section:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .variant-section label {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 8px;
            display: block;
        }

        .variant-section input[type="checkbox"] {
            transform: scale(1.2);
            accent-color: #0d6efd;
            margin-right: 6px;
            cursor: pointer;
        }

        .variant-hint {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 4px;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">products</a></li>
                <li class="breadcrumb-item active">create product</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Product</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.product.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.product.store') }}" method="post" enctype="multipart/form-data"
                    id="form">
                    @csrf
                    <section class="row">
                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name"
                                    value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control form-control-sm" id="category_id">
                                    <option value="">Select a category</option>
                                    @foreach ($productCategories as $productCategory)
                                        @include('admin.market.product.partials.category-option', [
                                            'category' => $productCategory,
                                            'prefix' => '',
                                        ])
                                    @endforeach
                                </select>
                            </div>
                            @error('category_id')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="brand_id">Brand</label>
                                <select name="brand_id" class="form-control form-control-sm" id="brand_id">
                                    <option value="">Select a brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            @if (old('brand_id') == $brand->id) selected @endif>
                                            {{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('brand_id')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <input type="hidden" class="form-control form-control-sm" name="tags" id="tags"
                                    value="{{ old('tags') }}">
                                <select class="select2 form-control form-control-sm myselect" id="select_tags" multiple>

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
                                <label for="base_price">Price</label>
                                <input type="text" class="form-control form-control-sm" name="base_price" id="base_price"
                                    value="{{ old('base_price') }}">
                            </div>
                            @error('base_price')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control form-control-sm" id="status">
                                    <option value="draft" @if (old('status') == 'draft') selected @endif>draft
                                    </option>
                                    <option value="published" @if (old('status') == 'published') selected @endif>published
                                    </option>

                                </select>
                            </div>
                            @error('status')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
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

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="published_at">Release date</label>
                                <input type="text" name="published_at" id="published_at"
                                    class="form-control form-control-sm" value="{{ old('published_at') }}"
                                    placeholder="Select date">
                                @error('published_at')
                                    <div class="text-danger mt-2" style="font-size: 12px;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea name="description" class="form-control form-control-sm" id="description">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        {{-- <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="has_color">Does it have color? (use for variant)</label>
                                <input type="checkbox" name="has_color" id="has_color" value="1">
                            </div>
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="has_size">Does it have size? (use for variant)</label>
                                <input type="checkbox" name="has_size" id="has_size" value="1">
                            </div>
                        </section> --}}

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group variant-section">
                                <label for="has_color">
                                    Does it have color? <small class="text-muted">(use for variant)</small>
                                </label>

                                <input type="checkbox" name="has_color" id="has_color" value="1">

                                <div class="variant-hint">
                                    If enabled, this product can have color-based variants.
                                </div>
                            </div>
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group variant-section">
                                <label for="has_size">
                                    Does it have size? <small class="text-muted">(use for variant)</small>
                                </label>

                                <input type="checkbox" name="has_size" id="has_size" value="1">

                                <div class="variant-hint">
                                    If enabled, this product can have size-based variants.
                                </div>
                            </div>
                        </section>




                        <section class="col-12 my-3 d-flex justify-content-end">
                            <button class="btn btn-primary">Submit</button>
                        </section>

                        @error('g-recaptcha-response')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </section>
                </form>
            </section>
        </section>
    @endsection
    @section('script')
        <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        {{-- published_date --}}
        <script>
            flatpickr("#published_at", {
                enableTime: true,
                dateFormat: "Y-m-d H:i", // مقدار واقعی‌ای که به سرور میره
                altInput: true, // یک فیلد فیک می‌سازه فقط برای نمایش به کاربر
                altFormat: "F j, Y H:i", // فرمت نمایش به کاربر
            });
        </script>

        {{-- ckEditor --}}
        <script>
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
        </script>

        {{-- select 2 --}}
        <script>
            $(document).ready(function() {
                var tags_input = $('#tags');
                var select_tags = $('#select_tags');
                var default_tags = tags_input.val();
                var default_data = null;

                if (tags_input.val() !== null && tags_input.val().length > 0) {
                    default_data = default_tags.split(',');
                }

                select_tags.select2({
                    placeholder: "Please enter your tags",
                    tags: true,
                    data: default_data,
                    language: {
                        noResults: function() {
                            return '';
                        }
                    }
                });
                select_tags.children('option').attr('selected', true).trigger('change');

                $('#form').submit(function(event) {
                    if (select_tags.val() !== null && select_tags.val().length > 0) {
                        var selectedSource = select_tags.val().join(',');
                        tags_input.val(selectedSource)
                    }
                })
            })
        </script>
    @endsection
