@extends('backEnd.admin.layouts.master')

@section('title', 'Landing Themes')

@section('content')

    <div class="container-xl">
        <div class="mt-3 d-print-none">

            <h3>Landing Themes</h3>

            <button class="btn btn-success btn-sm add-btn">
                <i class="ti ti-plus"></i> Add Theme
            </button>

            <div class="card mt-3">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Slug</th>
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php($i = 1)
                            @forelse ($themes as $item)
                                <tr @if ($i % 2 == 0) style="background:#f5f5f5" @endif>
                                    <td>{{ $i++ }}</td>
                                    <td>
                                        @if ($item->imageFile)
                                            <img src="{{ asset($item->imageFile->file_url) }}" width="80"
                                                class="rounded">
                                        @endif
                                    </td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->category?->title }}</td>
                                    <td>{{ $item->slug }}</td>

                                    <td class="w-1">
                                        <button
                                            class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit-btn"
                                            data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                            data-category_id="{{ $item->category_id }}"
                                            data-image="{{ $item->imageFile ? asset($item->imageFile->file_url) : '' }}">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>
                                        <a href="{{ route('admin.landing.theme.delete', $item->id) }}"
                                            class="btn-gradient-danger   border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                            onclick="return confirm('Delete this?')">
                                            <i class="ti ti-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-danger text-center fw-bold">No Data Found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="add-modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="card-header">
                    <h5>Add Theme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.landing.theme.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label required">Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Category</label>
                            <select name="category_id" class="form-select">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">Save</button>

                    </div>
                </form>

            </div>
        </div>
    </div>


    {{-- Edit Modal --}}
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="card-header">
                    <h5>Edit Theme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.landing.theme.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" id="id_e">

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label required">Title</label>
                            <input type="text" class="form-control" name="title" id="title_e" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Category</label>
                            <select name="category_id" id="category_id_e" class="form-select">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- IMAGE PREVIEW --}}
                        <div class="mb-3">
                            <label>Current Image</label>
                            <div id="edit-image-preview"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image (Optional)</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">Update</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection


@push('js')
    <script>
        $(document).ready(function() {

            $(".add-btn").click(function() {
                $("#add-modal").modal('show');
            });

            $(".edit-btn").click(function() {
                $("#id_e").val($(this).data("id"));
                $("#title_e").val($(this).data("title"));
                $("#category_id_e").val($(this).data("category_id"));

                let imageUrl = $(this).data("image");

                if (imageUrl) {
                    $("#edit-image-preview").html(`
                    <img src="${imageUrl}" width="120" class="rounded border">
                `);
                } else {
                    $("#edit-image-preview").html(`<span class="text-muted">No Image</span>`);
                }

                $("#edit-modal").modal('show');
            });

        });
    </script>
@endpush
