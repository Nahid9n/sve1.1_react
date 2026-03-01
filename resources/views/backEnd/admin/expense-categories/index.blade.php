@extends('backEnd.admin.layouts.master')
@section('title')
    Expense Categories
@endsection
@php
    $setting = DB::table('web_settings')->select('currency_sign')->where('id', 1)->first();
@endphp
@section('content')

    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>Expense Categories</h3>
                </div>
                <div class="col-12">
                    @can('expenses.category.create')
                        <a href="javascript:void(0);" class="btn btn-success btn-sm add_expense_category">
                            <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                            Add Category</a>
                    @endcan
                </div>
            </div>
            <div class="row row-deck row-cards mt-2">
                <!-- Page Header Close -->
                <div class="col-12 m-0">
                    <div class="card" style="border-top:none">
                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th style="width: 5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($data) > 0)
                                        @php($i = 1)
                                        @foreach ($data as $key => $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $key + 1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.expense.category.status', [$item->id, 0]) }}"
                                                            onclick="return confirm('Are you sure want to change this status?')"><span
                                                                class="badge bg-success">Inactive</span></a>
                                                    @else
                                                        <a href="{{ route('admin.expense.category.status', [$item->id, 1]) }}"
                                                            onclick="return confirm('Are you sure want to change this status?')"><span
                                                                class="badge bg-danger">Active</span></a>
                                                    @endif
                                                </td>
                                                <td class="w-1">

                                                    @can('expenses.category.edit')
                                                        <a href="javascript:void(0);"
                                                            class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1  edit_expense_category"
                                                            data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-status="{{ $item->status }}">
                                                            <i class="ti ti-edit"></i> &nbsp;Edit
                                                        </a>
                                                    @endcan
                                                    @can('expenses.category.delete')
                                                        <form action="{{ route('admin.expense.category.delete', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                onclick="return confirm('Are you sure want to delete this?')"><i
                                                                    class="ti ti-trash"></i>&nbsp;Delete</button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="10" class="text-center"> <span class="text-danger"><b>No data
                                                        found.</b></span></td>
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
    <!-- Add Modal -->
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Expense</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.expense.category.store') }}" method="POST">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success float-end">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Edit Expense</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.expense.category.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="col-12 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success float-end">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.add_expense_category', function() {
            $('#add_modal').modal('show');
        });

        $(document).on('click', '.edit_expense_category', function() {
            $('#edit_modal').modal('show');
            $('#id').val($(this).data('id'));
            $('#name').val($(this).data('name'));
            $('#status').val($(this).data('status'));
        });
    </script>
@endpush
