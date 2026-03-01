@extends('backEnd.admin.layouts.master')

@section('title', 'Categories')

@push('css')
    <style>
        .add_sub_cat_btn {
            padding: 1px !important;
            border-radius: 2px;
            text-align: center;
        }

        .add_sub_cat_btn i {
            margin-bottom: 1px;
        }
    </style>
@endpush

@php $data = $data ?? []; @endphp
@php
    $targetKeys = ['is_top_category']; // যেগুলো modal-এ দেখাতে চান
@endphp

@section('content')

    <div class="page-body">
        <div class="container-xl">
            <!-- Page Header -->
            <div class="d-flex flex-column align-items-start mb-3">
                <h2>Categories</h2>
                @can('categories.create')
                    <button class="btn btn-success btn-sm add_cat">
                        <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                        Add Category
                    </button>
                @endcan
            </div>

            <!-- Categories Table -->
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>SL.</th>
                                <th>Image</th>
                                <th class="text-start">Category & Sub-category</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @php $i=1; @endphp --}}
                            @forelse($data as $item)
                                @php
                                    $flags = $item->extra_fields ?? [];
                                @endphp
                                <tr>
                                    <td class="w-1">{{ $loop->iteration }}</td>
                                    <td class="w-5">
                                        <img src="{{ $item->file_url ? asset($item->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                            class="img-thumbnail" style="width:50px; height:50px; object-fit:cover;">
                                    </td>
                                    <td class="text-start">
                                        <b>{{ $item->category_name }}</b>
                                        <button class="btn btn-success btn-sm add_sub_cat_btn p-0 m-0"
                                            data-id="{{ $item->id }}" data-name="{{ $item->category_name }}">
                                            <i class="ti ti-plus"></i>
                                        </button>

                                        @if ($item->childrenRecursive->count())
                                            <div class="ms-4 mt-1">
                                                @include('backEnd.admin.categories.category_row', [
                                                    'children' => $item->childrenRecursive,
                                                    'targetKeys' => $targetKeys,
                                                ])
                                            </div>
                                        @endif
                                    </td>
                                    <td class="w-1">
                                        <input type="text" class="form-control form-control-sm positionInput"
                                            data-id="{{ $item->id }}" value="{{ $item->position }}">
                                    </td>
                                    <td class="w-1">
                                        <span class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="w-1">
                                        @can('categories.edit')
                                            <button
                                                class="btn-gradient-info border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit_cat_btn"
                                                data-id="{{ $item->id }}" data-name="{{ $item->category_name }}"
                                                data-slug="{{ $item->slug }}" data-status="{{ $item->status }}"
                                                data-is_show_home="{{ $item->is_show_home }}" {{-- EXTRA FLAGS --}}
                                                @foreach ($flags as $k => $v)
                                                    @if (in_array($k, $targetKeys))
                                                    data-{{ $k }}="{{ $v }}"
                                                    @endif @endforeach
                                                data-file_url="{{ $item->file_url }}">
                                                <i class="ti ti-edit"></i> Edit
                                            </button>
                                        @endcan
                                        @can('categories.delete')
                                            <a href="{{ route('admin.category.delete', $item->id) }}"
                                                class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="ti ti-trash"></i> Delete
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-danger fw-bold">No Data Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Include Modals --}}
    @include('backEnd.admin.categories.modals')

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.add_cat').click(function() {
                $('#add_cat_modal').modal('show');
            });

            // Open Edit Category
            $(document).on('click', '.edit_cat_btn', function() {

                $('#category_id_edit').val($(this).data('id'));
                $('#category_name_edit').val($(this).data('name'));
                $('#category_slug_edit').val($(this).data('slug'));
                $('#status_edit').val($(this).data('status'));
                $('#is_show_home_edit').val($(this).data('is_show_home'));

                $('#file_url_old').val($(this).data('file_url'));
                $('#img_append').attr('src', $(this).data('file_url') ||
                    'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg');

                // ------- APPLY FLAGS FOR CATEGORY -------
                @foreach ($targetKeys as $key)
                    if ($(this).data('{{ $key }}') !== undefined) {
                        $('#{{ $key }}_edit').val($(this).data('{{ $key }}'));
                    }
                @endforeach

                // ----------------------------------------

                $('#edit_cat_modal').modal('show');
            });

            // Open Add Subcategory
            $(document).on('click', '.add_sub_cat_btn', function() {
                $('#parent_name').val($(this).data('name'));
                $('#sub_parent').val($(this).data('id'));
                $('#sub_cat_name').val('');
                $('#sub_cat_slug').val('');
                $('#sub_slug_msg').text('');
                $('#add_sub_cat_modal').modal('show');
            });

            // Open Edit Subcategory
            $(document).on('click', '.edit_sub_cat_btn', function() {

                $('#category_id_e').val($(this).data('id'));
                $('#sub_parent_e').val($(this).data('parent_id'));
                $('#sub_category_name_e').val($(this).data('name'));
                $('#sub_category_slug_e').val($(this).data('slug'));
                $('#status_sub_e').val($(this).data('status'));
                $('#is_show_home_e').val($(this).data('is_show_home'));

                $('#img_append_sub').attr('src', $(this).data('file_url') ||
                    'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg');

                // ------- APPLY FLAGS FOR SUB CATEGORY -------
                @foreach ($targetKeys as $key)
                    if ($(this).data('{{ $key }}') !== undefined) {
                        $('#{{ $key }}_sub_edit').val($(this).data('{{ $key }}'));
                    }
                @endforeach

                // --------------------------------------------

                $('#sub_slug_msg_e').text('');
                $('#edit_sub_cat_modal').modal('show');
            });


            // Slug Maker
            function makeSlug(text) {
                return text.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-');
            }

            // Slug Checker
            function checkSlug(slug, id, msgBox) {
                $.get("{{ route('admin.category.slugCheck') }}", {
                    slug: slug,
                    id: id
                }, function(res) {
                    if (res.exists) {
                        $(msgBox).text('Slug already exists!').css('color', 'red');
                        $('.cat_btn').prop('disabled', true);
                        $('#saveSubSlugBtn').prop('disabled', true);
                    } else {
                        $(msgBox).text('');
                        $('.cat_btn').prop('disabled', false);
                        $('#saveSubSlugBtn').prop('disabled', false);
                    }
                });
            }

            // Add Category slug
            $('#cat_name').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $('#cat_slug').val(slug);
                checkSlug(slug, null, '#slug_msg');
            });
            $('#cat_slug').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $(this).val(slug);
                checkSlug(slug, null, '#slug_msg');
            });

            // Edit Category slug
            $('#category_name_edit').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $('#category_slug_edit').val(slug);
                let id = $('#category_id_edit').val();
                checkSlug(slug, id, '#slug_msg_edit');
            });
            $('#category_slug_edit').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $(this).val(slug);
                let id = $('#category_id_edit').val();
                checkSlug(slug, id, '#slug_msg_edit');
            });

            // Save Category Slug AJAX
            $('#saveBtn').on('click', function() {
                let id = $('#category_id_edit').val();
                let slug = $('#category_slug_edit').val();
                if (slug == '') {
                    $('#slug_msg_edit').html("<span class='text-danger'>Slug required!</span>");
                    return;
                }
                $.post("{{ route('admin.category.slug.update') }}", {
                    id: id,
                    slug: slug,
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    if (res.status) $('#slug_msg_edit').html(
                        "<span class='text-success'>Slug updated!</span>");
                    else $('#slug_msg_edit').html("<span class='text-danger'>" + res.msg +
                        "</span>");
                });
            });

            // Add Subcategory slug
            $('#sub_cat_name').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $('#sub_cat_slug').val(slug);
                checkSlug(slug, null, '#sub_slug_msg');
            });
            $('#sub_cat_slug').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $(this).val(slug);
                checkSlug(slug, null, '#sub_slug_msg');
            });

            // Edit Subcategory slug
            $('#sub_category_name_e').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $('#sub_category_slug_e').val(slug);
                let id = $('#category_id_e').val();
                checkSlug(slug, id, '#sub_slug_msg_e');
            });
            $('#sub_category_slug_e').on('keyup', function() {
                let slug = makeSlug($(this).val());
                $(this).val(slug);
                let id = $('#category_id_e').val();
                checkSlug(slug, id, '#sub_slug_msg_e');
            });

            // Save Subcategory Slug AJAX
            $('#saveSubSlugBtn').on('click', function() {
                let id = $('#category_id_e').val();
                let slug = $('#sub_category_slug_e').val();
                if (slug == '') {
                    $('#sub_slug_msg_e').html("<span class='text-danger'>Slug required!</span>");
                    return;
                }
                $.post("{{ route('admin.category.slug.update') }}", {
                    id: id,
                    slug: slug,
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    if (res.status) $('#sub_slug_msg_e').html(
                        "<span class='text-success'>Slug updated!</span>");
                    else $('#sub_slug_msg_e').html("<span class='text-danger'>" + res.msg +
                        "</span>");
                });
            });

        });
    </script>
    <script>
        $(document).on('blur', '.positionInput', function() {
            let id = $(this).data('id');
            let position = $(this).val();
            $.ajax({
                url: "{{ route('category.update.position') }}",
                method: "POST",
                data: {
                    id: id,
                    position: position,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Position updated!',
                            timer: 1200,
                            showConfirmButton: false
                        });
                    }
                },

                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to update!',
                        timer: 1500
                    });
                }
            });
        });


        // ENTER চাপলে blur trigger
        $(document).on('keypress', '.positionInput', function(e) {
            if (e.which === 13) {
                $(this).blur(); // triggers the update ajax
            }
        });
    </script>
@endsection
