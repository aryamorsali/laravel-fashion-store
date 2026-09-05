@extends('admin.layouts.master2')

@section('head-tag')
    <title>Edit Warehouse</title>
@endsection

@section('content')
    <section class="container-fluid px-0">
        <nav style="background-color: #eee; height: 2.25rem" class="my-4 rounded ps-2" aria-label="breadcrumb">
            <ol class="breadcrumb p-1 ">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}" style="text-decoration: none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Market</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Warehouses</a></li>
                <li class="breadcrumb-item active">Edit Warehouse</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Edit Warehouse</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.market.warehouse.index') }}" class="btn btn-dark btn-sm">Cancel</a>
            </section>

            <section>
                <form action="{{ route('admin.market.warehouse.update', $warehouse) }}" method="post">
                    @csrf
                    @method('put')
                    <section class="row">
                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name"
                                    value="{{ old('name', $warehouse->name) }}">
                            </div>
                            @error('name')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </section>

                        <section class="col-12 col-md-6 my-3">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control form-control-sm" name="address" id="address"
                                    placeholder="(optional)" value="{{ old('address', $warehouse->address) }}">
                            </div>
                            @error('address')
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

            $('.myselect').on('change', function() {
                var selected = $(this).val(); // آرایه انتخاب شده‌ها
                $('#tags').val(selected ? selected.join(',') : ''); // اگر خالیه، رشته خالی بذار
            });
        </script>
    @endsection
