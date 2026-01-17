@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Post</title>
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
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">content</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">post</a></li>
                <li class="breadcrumb-item active">edit post</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Post</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.content.post.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.content.post.update', $post) }}" method="post" enctype="multipart/form-data"
                    id="form">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control form-control-sm" name="title" id="title"
                                    value="{{ old('title', $post->title) }}">
                            </div>
                            @error('title')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" class="form-control form-control-sm" id="category_id">
                                    @foreach ($postCategories as $postCategory)
                                        <option value="{{ $postCategory->id }}"
                                            @if (old('category_id', $post->category_id) == $postCategory->id) selected @endif>
                                            {{ $postCategory->name }}
                                        </option>
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
                                <label for="status">Status</label>
                                <select name="status" class="form-control form-control-sm" id="status">
                                    <option value="0" @if (old('status', $post->status) == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('status', $post->status) == 1) selected @endif>active
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
                        <section class="row my-2">
                            @php
                                $number = 2;
                            @endphp
                            @foreach ($post->image['blogArray'] as $key => $value)
                                <section class="col-md-{{ 6 / $number }} mr-5">
                                    <div class="form-check  p-1">
                                        <input type="radio" name="currentImage" class="form-check-input"
                                            value="{{ $key }}" id="{{ $number }}"
                                            @if ($post->image['currentImage'] == $key) checked @endif>
                                        <label for="{{ $number }}" class="form-check-label mx-3">
                                            <img src="{{ asset($value) }}" class="img-fluid rounded w-100" alt="">
                                        </label>
                                    </div>
                                </section>
                                @php
                                    $number++;
                                @endphp
                            @endforeach
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="commentable">Commentable</label>
                                <select name="commentable" class="form-control form-control-sm" id="commentable">
                                    <option value="0" @if (old('commentable', $post->commentable) == 0) selected @endif>inactive
                                    </option>
                                    <option value="1" @if (old('commentable', $post->commentable) == 1) selected @endif>active
                                    </option>
                                </select>
                            </div>
                            @error('commentable')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="published_at">Release date</label>
                                <input type="text" name="published_at" id="published_at"
                                    class="form-control form-control-sm"
                                    value="{{ old('published_at', $post->published_at) }}" placeholder="Select date">
                                @error('published_at')
                                    <div class="text-danger mt-2" style="font-size: 12px;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <input type="hidden" class="form-control form-control-sm" name="tags" id="tags"
                                    value="{{ old('tags', $post->tags) }}">
                                <select class="select2 form-control form-control-sm myselect" id="select_tags" multiple>

                                </select>
                            </div>
                            @error('tags')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>


                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="">Summary of the post</label>
                                <textarea name="summary" class="form-control form-control-sm" id="summary">
                                      {{ old('summary', $post->summary) }}
                                    </textarea>
                            </div>
                            @error('summary')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea name="body" class="form-control form-control-sm" id="body">{{ old('body', $post->body) }}</textarea>
                            </div>
                            @error('body')
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

        {{-- ckeditor --}}
        <script>
            // انتخاب هر دو المان با id 'post' و 'summary'
            const editors = document.querySelectorAll('#body, #summary');

            editors.forEach(editor => {
                ClassicEditor
                    .create(editor)
                    .catch(error => {
                        console.error(error);
                    });
            });
        </script>

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
