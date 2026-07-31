@extends('admin.layouts.master2')

@section('head-tag')
    <title>Create Admin Role</title>
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
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none">Users</a></li>
                <li class="breadcrumb-item active">Create Admin Role</li>
            </ol>
        </nav>
        <section class="main-body-container">
            <section>
                <h3 class="mt-2">Create Admin Role</h3>
            </section>
            <section class="d-flex justify-content-between align-items-center mt-3 mb-3 border-bottom pb-3">
                <a href="{{ route('admin.user.admin.index') }}" class="btn btn-dark btn-sm">Back</a>
            </section>

            <section>
                <form action="{{ route('admin.user.admin.role.store', $admin) }}" method="post"
                    enctype="multipart/form-data" id="form">
                    @csrf
                    <section class="row">

                        <section class="col-12 my-3">
                            <div class="form-group">
                                <label for="tags">Roles</label>
                                <select class="select2 form-control form-control-sm" id="select_roles" multiple
                                    name="roles[]">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            @foreach ($admin->roles as $user_role)
                                                  @if ($user_role->id === $role->id) selected  @endif @endforeach>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('roles')
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
            var select_roles = $('#select_roles');
            select_roles.select2({
                placeholder: 'Please enter roles',
                multiple: true,
            })
        </script>
    @endsection
