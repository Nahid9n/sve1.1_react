@extends('backEnd.admin.layouts.master')

@section('title')
    Media
@endsection

@php
    $data = $data ?? [];
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row ">
                <div class="col-12">
                    <h3>Media </h3>
                </div>
                <div class="col-12">
                    @can('media.create')
                        <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn">
                            <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                            Add Media</a>
                    @endcan
                </div>
            </div>
            <div class="row mt-3">
                <style>
                    .media-card {
                        position: relative;
                        border-radius: 10px;
                        overflow: hidden;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                        transition: 0.3s;
                        background: #fff;
                    }

                    .media-card:hover {
                        transform: translateY(-4px);
                    }

                    .media-card img {
                        width: 100%;
                        height: 160px;
                        object-fit: cover;
                    }

                    .media-body {
                        padding: 10px;
                    }

                    .media-title {
                        font-size: 13px;
                        font-weight: 600;
                        margin-bottom: 3px;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }

                    .media-type {
                        font-size: 11px;
                        color: #777;
                    }

                    /* Action Icons */
                    .media-actions {
                        position: absolute;
                        top: 6px;
                        right: 6px;
                        display: flex;
                        gap: 5px;
                        z-index: 2;
                    }

                    .media-actions a {
                        background: rgba(0, 0, 0, 0.6);
                        color: #fff;
                        padding: 5px;
                        border-radius: 50%;
                        font-size: 11px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        transition: 0.3s;
                    }

                    .media-actions a:hover {
                        background: #0d6efd;
                    }
                </style>

                @if ($data->count() > 0)
                    @foreach ($data as $item)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                            <div class="media-card">

                                {{-- Action Icons --}}
                                <div class="media-actions">
                                    @can('media.edit')
                                        <a href="javascript:void(0);" class="edit_btn" data-id="{{ $item->id }}"
                                            data-url="{{ asset($item->file_url) }}">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                    @endcan

                                    @can('media.delete')
                                        <a href="{{ route('admin.media.delete', $item->id) }}"
                                            onclick="return confirm('Are you sure to delete this?')">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    @endcan
                                </div>

                                {{-- Image --}}
                                <img src="{{ asset($item->file_url) }}" alt="">

                                {{-- Always Visible Info --}}
                                <div class="media-body">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <small class="text-truncate" style="max-width:120px;">
                                            {{ asset($item->file_url) }}
                                        </small>

                                        <div class="d-flex gap-1">

                                            {{-- Open Link --}}
                                            <a href="{{ asset($item->file_url) }}" target="_blank"
                                                class="btn btn-sm btn-light p-1">
                                                <i class="ti ti-link"></i>
                                            </a>

                                            {{-- Copy Link --}}
                                            <button type="button" class="btn btn-sm btn-light p-1"
                                                onclick="copyToClipboard('{{ asset($item->file_url) }}', this)">
                                                <i class="ti ti-copy"></i>
                                            </button>

                                        </div>
                                    </div>

                                    <div class="media-title mt-1" title="{{ $item->file_original_name }}">
                                        {{ $item->file_original_name }}
                                    </div>

                                </div>

                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center text-danger">
                        No Media Found!
                    </div>
                @endif

            </div>
        </div>
    </div>


    <!-- Add Modal -->
    <div class="modal  fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Media</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.media.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>

                        <div class="col-12 mb-3">
                            <input type="submit" class="btn btn-success btn-sm" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal  fade" id="edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Edit Media</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.media.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="img_id" name="id">
                        <div class="col-12 mb-3">
                            <div class="card w-50 m-auto">
                                <img id="img_append" src="" alt="">
                            </div>
                            <br>
                            <input type="file" class="form-control" id="file" name="file" required>
                        </div>

                        <div class="col-12 mb-3">
                            <input type="submit" class="btn btn-success btn-sm" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).on('click', '.edit_btn', function() {
            $('#edit_modal').modal('show');
            $('#img_id').val($(this).data('id'));
            $('#img_append').attr('src', $(this).data('url'));
        });

        $(document).on('click', '.add_btn', function() {
            $('#add_modal').modal('show');
        });
    </script>
    <script>
        function copyToClipboard(text, btn) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    btn.innerHTML = '<i class="ti ti-check text-success"></i>';
                    setTimeout(() => {
                        btn.innerHTML = '<i class="ti ti-copy"></i>';
                    }, 1500);
                });
            } else {
                // Fallback for old browsers
                let tempInput = document.createElement("input");
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);

                btn.innerHTML = '<i class="ti ti-check text-success"></i>';
                setTimeout(() => {
                    btn.innerHTML = '<i class="ti ti-copy"></i>';
                }, 1500);
            }
        }
    </script>
@endpush
