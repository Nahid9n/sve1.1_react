@extends('backEnd.admin.layouts.master')

@section('title')
    Sliders
@endsection

@php
    $data = $data ?? [];
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row ">
                <div class="col-12">
                    <h3>Sliders</h3>
                </div>

                <div class="col-12 d-flex justify-content-between">
                    @can('sliders.create')
                        <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn d-flex align-items-center">
                            <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                            Add Slider</a>
                    @endcan

                </div>
            </div>
            <div class="row row-deck row-cards mt-2">
                <!-- Page Header Close -->
                <div class="col-12 m-0">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th class="w-1">SL.</th>
                                        <th>Image</th>
                                        <th>Slider URL</th>
                                        <th>Position</th>
                                        <th class="w-1">Status</th>
                                        <th class="w-1">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <img width="150"
                                                        src="{{ $item->get_img ? asset($item->get_img->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                        alt="">
                                                </td>
                                                <td>
                                                    {{ $item->slider_url }}
                                                </td>
                                                <td>
                                                    <div style="width:100px">
                                                        <input type="number" value="{{ $item->position }}"
                                                            data-id="{{ $item->id }}"
                                                            class="form-control position-input">
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.slider.status', $item->id) }}"
                                                            class="badge bg-success"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Active</a>
                                                    @else
                                                        <a href="{{ route('admin.slider.status', $item->id) }}"
                                                            class="badge bg-danger"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Inactive</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @can('sliders.edit')
                                                            <a href="javascript:void(0)"
                                                                class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1  edit_btn"
                                                                data-id="{{ $item->id }}"
                                                                data-img_id="{{ $item->slider_image }}"
                                                                data-slider_url="{{ $item->slider_url }}"
                                                                data-url="{{ $item->get_img ? asset($item->get_img->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                                data-status="{{ $item->status }}"><i
                                                                    class="ti ti-edit"></i>Edit
                                                            </a>
                                                        @endcan
                                                        @can('sliders.delete')
                                                            <a href="{{ route('admin.sliders.delete', $item->id) }}"
                                                                onclick="return confirm('Are you sure to delete this?')"
                                                                class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"><i
                                                                    class="ti ti-trash"></i>Delete</a>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-danger font-weight-bold">No Data
                                                Found!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Add Modal -->
    <div class="modal  fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Slider</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.sliders.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label" for="slider_url">Slider URL</label>
                            <input type="text" class="form-control" id="slider_url" name="slider_url" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="slider_image">Slider Image</label>
                            <input type="file" class="form-control" id="slider_image" name="slider_image" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3 ">
                            <input type="submit" class="btn btn-success float-end" value="Create">
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
                    <h5 class="modal-title">Edit Slider</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.sliders.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="slider_id_e">

                        <div class="col-12 mb-3">
                            <label class="form-label" for="slider_url_e">Slider URL</label>
                            <input type="text" class="form-control" id="slider_url_e" name="slider_url" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="slider_image_e">Slider Image</label>
                            <div class="card w-50">
                                <img id="img_append" src="" alt="">
                            </div>
                            <br>
                            <input type="file" class="form-control" id="slider_image_e" name="slider_image">
                            <input type="hidden" id="slider_image_old" name="slider_image_old">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="status_e">Status</label>
                            <select name="status" id="status_e" class="form-control" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 ">
                            <input type="submit" class="btn btn-success float-end" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.add_btn', function() {
            $('#add_modal').modal('show');
        });

        $(document).on('click', '.edit_btn', function() {
            $('#edit_modal').modal('show');
            $('#slider_id_e').val($(this).data('id'));
            $('#slider_image_old').val($(this).data('img_id'));
            $('#img_append').attr('src', $(this).data('url'));
            $('#slider_url_e').val($(this).data('slider_url'));
            $('#status_e').val($(this).data('status'));
        });

        // $('.edit_btn').on('click', function() {

        // })
    </script>
    <script>
        $('.position-input').on('change', function() {

            let position = $(this).val();
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.slider.update.position') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    position: position
                },
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Position updated!',
                            timer: 1200,
                            showConfirmButton: false
                        })
                    }
                },
                error: function() {
                    alert('Something went wrong!');
                }
            });
        });
    </script>
@endpush
