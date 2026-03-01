@extends('backEnd.admin.layouts.master')

@section('title')
    Themes
@endsection

@php
    $data = $data ?? [];
@endphp

@section('css')
    <style>
        .row>* {
            padding-right: calc(var(--tblr-gutter-x) * .4);
            padding-left: calc(var(--tblr-gutter-x) * .4);
        }
    </style>
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>Themes</h3>
                </div>
            </div>

            @can('theme.list')
                <div class="row mb-2">
                    <div class="col-12">
                        <a href="javascript:void(0);" class="btn btn-success btn-sm add_theme">
                            <i class="ti ti-plus me-1"></i> Add Theme
                        </a>
                    </div>
                </div>
            @endcan

            <div class="row row-deck row-cards mt-2">
                <div class="col-12">
                    <div class="card" style="border-top: none">
                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>

                        <div class="table-responsive">
                            <table class="table align-top card-table datatable">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Preview</th>
                                        <th>URL</th>
                                        <th>Name</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $i++ }}</td>
                                                <td>
                                                    <img src="{{ asset($item->image) }}" width="100"
                                                        style="border-radius:8px">
                                                </td>
                                                <td>
                                                    @if ($item->is_active == 1)
                                                        <a href="{{ route('home') }}"
                                                            target="_blank">{{ $item->path }}</a>
                                                    @else
                                                        <a href="{{ route('theme.home', $item->path) }}"
                                                            target="_blank">{{ $item->path }}</a>
                                                    @endif
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                {{-- <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.themes.status', $item->id) }}"
                                                            class=" btn btn-outline-success btn-sm mb-1"
                                                            onclick="return confirm('Are you sure to status change this theme?')">
                                                            Published
                                                        </a>
                                                    @else
                                                        <a href="{{ route('admin.themes.status', $item->id) }}"
                                                            class="btn btn-outline-danger btn-sm mb-1"
                                                            onclick="return confirm('Are you sure to status change this theme?')">
                                                            Unpublished
                                                        </a>
                                                    @endif
                                                </td> --}}
                                                <td class="w-1 text-center">
                                                    {{-- <a href="{{ route('admin.themes.activate', $item->id) }}"
                                                        class="btn btn-outline-success btn-sm mb-1 w-100"
                                                        onclick="return confirm('Are you sure to activate this theme? All other themes will be removed!')">
                                                        <i class="ti ti-check"></i> Activate
                                                    </a> --}}
                                                    @can('theme.status')
                                                        @if ($item->is_active == 1)
                                                            <a href="{{ route('admin.themes.activate', ['id' => $item->id, 'status' => 0]) }}"
                                                                class="btn-gradient-success  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                onclick="return confirm('Are you sure to Deactivate this theme?')">
                                                                Activated
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.themes.activate', ['id' => $item->id, 'status' => 1]) }}"
                                                                class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                onclick="return confirm('Are you sure to activate this theme? All other themes will be removed!')">
                                                                Deactivated
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    @can('theme.delete')
                                                        <form action="{{ route('admin.themes.destroy', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                onclick="return confirm('Are you sure want to delete this theme?')">
                                                                <i class="ti ti-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-danger fw-bold">No Theme Found!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Theme Modal -->
    <div class="modal fade" id="add_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Theme</h5>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.themes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Theme Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Theme Path Name</label>
                            <input type="text" name="path" class="form-control" required>
                            <small class="text-danger">* Example: theme-1, theme-2, theme-3 etc.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preview Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success float-end w-100">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Theme Modal -->
    {{-- <div class="modal fade" id="edit_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Edit Theme</h5>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.themes.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Theme Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preview Image</label>
                            <input type="file" name="preview_image" class="form-control">
                            <img id="preview_img" src="" width="100" class="mt-2 rounded">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-sm w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@push('js')
    <script>
        $(document).on('click', '.add_theme', function() {
            $('#add_modal').modal('show');
        });
    </script>
@endpush
