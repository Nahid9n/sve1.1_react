@extends('backEnd.admin.layouts.master')

@section('title', 'Landing Categories')

@section('content')

    <div class="container-xl">

        <div class="mt-3 d-print-none">
            <h3>Landing Categories</h3>

            <button class="btn btn-success btn-sm add-btn">
                <i class="ti ti-plus"></i> Add Category
            </button>

            <div class="card mt-3">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php($i = 1)
                            @forelse($categories as $item)
                                <tr @if ($i % 2 == 0) style="background:#f5f5f5" @endif>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->slug }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="w-1">
                                        <button
                                            class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit-btn"
                                            data-id="{{ $item->id }}" data-title="{{ $item->title }}"
                                            data-status="{{ $item->status }}">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>
                                        <a href="{{ route('admin.landing.category.delete', $item->id) }}"
                                            class="btn-gradient-danger border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                            onclick="return confirm('Delete this?')">
                                            <i class="ti ti-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-danger fw-bold">No Data Found!</td>
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="card-header">
                    <h5>Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.landing.category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label required">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">Save</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="edit-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="card-header">
                    <h5>Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('admin.landing.category.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id_e">

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label required">Title</label>
                            <input type="text" name="title" id="title_e" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Status</label>
                            <select name="status" id="status_e" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                $("#status_e").val($(this).data("status"));
                $("#edit-modal").modal('show');
            });
        });
    </script>
@endpush
