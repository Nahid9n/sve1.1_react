@extends('backEnd.admin.layouts.master')

@section('title')
    Shipping Methods
@endsection

@php
    $data = $data ?? [];
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>Shipping Methods</h3>
                </div>
                <div class="col-12">
                    <h2 class="page-title">
                        @can('shipping_method.create')
                            <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn">
                                <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                                Add Shipping Method</a>
                        @endcan
                    </h2>
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
                                        <th>Shipping Method Type</th>
                                        <th>Shipping Method Text</th>
                                        <th>Shipping Method Amount</th>
                                        <th style="width: 5%">Status</th>
                                        <th style="width: 5%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $i++ }}</td>
                                                <td>{{ $item->type }}</td>
                                                <td>{{ $item->text }}</td>
                                                <td>{{ $item->amount }}</td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.shipping.methods.status', $item->id) }}"
                                                            class="badge bg-success"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Active</a>
                                                    @else
                                                        <a href="{{ route('admin.shipping.methods.status', $item->id) }}"
                                                            class="badge bg-danger"
                                                            onclick="return confirm('Are You Sure To Change This?')">
                                                            Inactive</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @can('shipping_method.edit')
                                                            <a href="javascript:void(0)"
                                                                class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit_btn"
                                                                data-id="{{ $item->id }}" data-type="{{ $item->type }}"
                                                                data-text="{{ $item->text }}"
                                                                data-amount="{{ $item->amount }}"
                                                                data-status="{{ $item->status }}">
                                                                <i class="ti ti-edit"></i>Edit
                                                            </a>
                                                        @endcan

                                                        @can('shipping_method.delete')
                                                            <a href="{{ route('admin.shipping_methods.delete', $item->id) }}"
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
                                            <td colspan="6" class="text-center text-danger font-weight-bold">No Data
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
                    <form action="{{ route('admin.shipping_methods.store') }}" method="post">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label" for="type">Shipping Method Type</label>
                            <input type="text" class="form-control" id="type" name="type"
                                placeholder="eg. ঢাকার ভিতরে" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="text">Shipping Method Text</label>
                            <input type="text" class="form-control" id="text" name="text"
                                placeholder="eg. ঢাকায় ডেলিভারি খরচ" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="amount">Shipping Method Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="eg. 60"
                                required>
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
                    <form action="{{ route('admin.shipping_methods.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id_e">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="type_e">Shipping Method Type</label>
                            <input type="text" class="form-control" id="type_e" name="type"
                                placeholder="eg. ঢাকার ভিতরে" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="text_e">Shipping Method Text</label>
                            <input type="text" class="form-control" id="text_e" name="text"
                                placeholder="eg. ঢাকায় ডেলিভারি খরচ" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="amount_e">Shipping Method Amount</label>
                            <input type="text" class="form-control" id="amount_e" name="amount"
                                placeholder="eg. 60" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="status_e">Status</label>
                            <select name="status" id="status_e" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-12 mb-3">
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
            $('#id_e').val($(this).data('id'));
            $('#type_e').val($(this).data('type'));
            $('#text_e').val($(this).data('text'));
            $('#amount_e').val($(this).data('amount'));
            $('#status_e').val($(this).data('status'));
        });
    </script>
@endpush
