@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

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

        /* /////////////////////////////////////////// */

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
        }

        .variant-section .locked-message {
            background: rgba(255, 243, 205, 0.8);
            color: #856404 !important;
            border-radius: 6px;
            padding: 6px 10px;
            margin-top: 6px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .variant-section .locked-message i {
            color: #856404;
            font-size: 0.95rem;
        }

        /* ✅ حالت غیرفعال واضح‌تر */
        input[type="checkbox"]:disabled {
            cursor: not-allowed;
            opacity: 0.6;
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
                <li class="breadcrumb-item active">edit product</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Product</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.product.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.product.update', $product) }}" method="post"
                    enctype="multipart/form-data" id="form">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name"
                                    value="{{ old('name', $product->name) }}">
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
                                            'product' => $product ?? null,
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
                                            @if (old('brand_id', $product->brand_id ?? '') == $brand->id) selected @endif>
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

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label>Tags</label>
                                <select class="select2 form-control form-control-sm" id="select_tags" multiple
                                    name="tags[]">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            @if (in_array($tag->id, old('tags', $product->tags->pluck('id')->toArray()))) selected @endif>
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
                                <label for="base_price">Price</label>
                                <input type="text" class="form-control form-control-sm" name="base_price" id="base_price"
                                    value="{{ old('base_price', $product->base_price) }}">
                            </div>
                            @error('base_price')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                @php
                                    $canPublish = $product->variants()->exists();
                                @endphp
                                <label for="status">Status</label>
                                <input type="hidden" name="status" value="{{ $product->status }}">
                                <select name="status" class="form-control form-control-sm" id="status"
                                    {{ $canPublish ? '' : 'disabled' }}>
                                    <option value="draft" @if (old('status', $product->status) == 'draft') selected @endif>draft
                                    </option>
                                    <option value="published" @if (old('status', $product->status) == 'published') selected @endif>published
                                    </option>
                                </select>
                            </div>
                            @if (!$canPublish)
                                <small class="text-warning">
                                    To publish this product, you must first create at least one variant.
                                </small>
                            @endif
                            @error('status')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="image" class="fw-bold mb-2">Image</label>
                                <input type="file" class="form-control form-control-sm" name="image" id="image">
                                @error('image')
                                    <div class="text-danger mt-2" style="font-size: 12px;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            {{-- نمایش تصاویر موجود --}}
                            @if ($product->image)
                                <section class="row mt-4 my-2">
                                    @foreach ($product->image['indexArray'] as $key => $value)
                                        <section class="col-md-3 col-sm-6 mb-3">
                                            <input type="radio" name="currentImage" id="image-{{ $key }}"
                                                value="{{ $key }}" @checked($product->image['currentImage'] == $key)>
                                            <label for="image-{{ $key }}" class="d-block text-center">
                                                <img src="{{ asset($value) }}" class="img-fluid rounded image-option"
                                                    alt="">
                                            </label>
                                        </section>
                                    @endforeach
                                </section>
                            @endif
                        </section>

                        {{-- بخش تاریخ انتشار --}}
                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="published_at" class="fw-bold mb-2">Release date</label>
                                <input type="text" name="published_at" id="published_at"
                                    class="form-control form-control-sm"
                                    value="{{ old('published_at', $product->published_at) }}" placeholder="Select date">
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
                                <textarea name="description" class="form-control form-control-sm" id="description">{{ old('description', $product->description) }}</textarea>
                            </div>
                            @error('description')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        @php
                            // فقط واریانت‌های واقعی قفل‌کننده هستند
                            $hasColorVariants = $product->variants()->whereNotNull('color_id')->exists();

                            $hasSizeVariants = $product->variants()->whereNotNull('size_id')->exists();
                        @endphp

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group variant-section">
                                <label for="has_color">
                                    Does it have color? <small class="text-muted">(use for variant)</small>
                                </label>

                                <input type="checkbox" id="has_color" name="has_color" value="1"
                                    {{ old('has_color', $product->has_color) ? 'checked' : '' }}
                                    {{ $hasColorVariants || $hasSizeVariants ? 'disabled' : '' }}>

                                @if ($hasColorVariants || $hasSizeVariants)
                                    <input type="hidden" name="has_color" value="{{ (int) $product->has_color }}">
                                    <div class="locked-message">
                                        <i class="fa fa-lock"></i>
                                        This product has variants; to change the nature, first delete its variants.
                                    </div>
                                @endif
                            </div>
                        </section>


                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group variant-section">
                                <label for="has_size">
                                    Does it have size? <small class="text-muted">(use for variant)</small>
                                </label>

                                <input type="checkbox" id="has_size" name="has_size" value="1"
                                    {{ old('has_size', $product->has_size) ? 'checked' : '' }}
                                    {{ $hasColorVariants || $hasSizeVariants ? 'disabled' : '' }}>

                                @if ($hasColorVariants || $hasSizeVariants)
                                    <input type="hidden" name="has_size" value="{{ (int) $product->has_size }}">
                                    <div class="locked-message">
                                        <i class="fa fa-lock"></i>
                                        This product has variants; to change the nature, first delete its variants.
                                    </div>
                                @endif
                            </div>
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
            var select_tags = $('#select_tags');
            select_tags.select2({
                placeholder: 'Please enter tags (optional)',
                multiple: true,
            })
        </script>
    @endsection
