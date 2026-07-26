@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Variant</title>
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
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">product</a></li>
                <li class="breadcrumb-item active">create variant</li>
            </ol>
        </nav>
        <section class="main-body-container">
            @include('admin.alerts.alert-section.warning')

            <section>
                <h3 class="mt-2">Create Variant</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.variant.index', $product) }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.variant.store', $product) }}" method="post">
                    @csrf
                    <section class="row">

                        <section class="col-12 col-md-6 mt-3">
                            <div class="form-group">
                                <label>Product</label>
                                <p
                                    style="background-color: white; color: #333;  border: 1px solid #c7d2fe; border-radius: 6px; padding: 6px 12px; font-size: 14px;">
                                    {{ $product->name }}
                                </p>
                            </div>
                        </section>
                        @if ($product->has_color == 1)
                            <section class="col-12 col-md-6 my-3">
                                <div class="form-group">
                                    <label>colors</label>
                                    <select class="select2 form-control form-control-sm" id="select_colors" multiple
                                        name="colors[]">
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}"
                                                @if (is_array(old('colors')) && in_array($color->id, old('colors'))) selected @endif>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('colors')
                                    <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </section>
                        @endif

                        @if ($product->has_size == 1)
                            <section class="col-12 col-md-6 my-3">
                                <div class="form-group">
                                    <label>sizes</label>
                                    <select class="select2 form-control form-control-sm" id="select_sizes" multiple
                                        name="sizes[]">
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}"
                                                @if (is_array(old('sizes')) && in_array($size->id, old('sizes'))) selected @endif>
                                                {{ $size->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('sizes')
                                    <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </section>
                        @endif

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control form-control-sm" name="price" id="price"
                                    value="{{ old('price') }}">
                            </div>
                            @error('price')
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
        <script>
            var select_colors = $('#select_colors');
            select_colors.select2({
                placeholder: 'Please enter colors (optional)',
                multiple: true,
            })

            var select_sizes = $('#select_sizes');
            select_sizes.select2({
                placeholder: 'Please enter sizes (optional)',
                multiple: true,
            })
        </script>
    @endsection
