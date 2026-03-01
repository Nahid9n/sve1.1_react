@extends('backEnd.admin.layouts.master')

@section('title')
    Couriers
@endsection

@php
    $data = $data ?? [];
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>Couriers</h3>
                </div>
                <div class="col-12">
                    {{-- <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn">
                        <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                        Add Courier</a> --}}
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
                                        <th>SL.</th>
                                        <th>Courier Name</th>
                                        <th style="width: 5%">Status</th>
                                        {{-- <th style="width: 5%">Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $i++ }}</td>
                                                <td>{{ $item->courier_name }}</td>

                                                <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.courier.status', $item->id) }}"
                                                            class="badge bg-success"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Active</a>
                                                    @else
                                                        <a href="{{ route('admin.courier.status', $item->id) }}"
                                                            class="badge bg-danger"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Inactive</a>
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    <div class="d-flex flex-column">

                                                        <a href="javascript:void(0)"
                                                            class="btn btn-outline-success btn-sm mb-1 edit_btn"
                                                            data-id="{{ $item->id }}"
                                                            data-courier_name="{{ $item->courier_name }}"
                                                            data-courier_charge="{{ $item->courier_charge }}"
                                                            data-is_city="{{ $item->is_city }}"
                                                            data-is_zone="{{ $item->is_zone }}"
                                                            data-status="{{ $item->status }}"><i
                                                                class="ti ti-edit"></i>&nbsp;Edit
                                                        </a>
                                                        <a href="{{ route('admin.courier.delete', $item->id) }}"
                                                            onclick="return confirm('Are you sure to delete this?')"
                                                            class="btn btn-outline-danger btn-sm"><i
                                                                class="ti ti-trash"></i>&nbsp;Delete</a>
                                                    </div>
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center text-danger font-weight-bold">No Data
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
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Courier</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.courier.store') }}" method="post">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label" for="courier_name">Courier Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="courier_name" name="courier_name" required>
                        </div>


                        <div class="col-12 mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3 ">
                            <input type="submit" class="btn btn-success btn-sm" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Edit Courier</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.courier.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="courier_name_e">Courier Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="courier_name_e" name="courier_name" required>
                        </div>



                        <div class="col-12 mb-3">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status_e" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3 ">
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
        $(document).on('click', '.add_btn', function() {
            $('#add_modal').modal('show');
        });

        $(document).on('click', '.edit_btn', function() {
            $('#edit_modal').modal('show');
            $('#id').val($(this).data('id'));
            $('#courier_name_e').val($(this).data('courier_name'));

            $('#status_e').val($(this).data('status'));
        });
    </script>
@endpush
