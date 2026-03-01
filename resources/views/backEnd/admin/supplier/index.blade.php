@extends('backEnd.admin.layouts.master')

@section('title')
    Supplier List
@endsection

@php
    $data = $data ?? [];
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>Supplier List</h3>
                </div>
                <div class="col-12">
                    @can('suppliers.create')
                        <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn"> <i class="ti ti-plus me-1"
                                style="margin-bottom: 2px"></i>
                            Add
                            Supplier</a>
                    @endcan
                </div>
            </div>
            <div class="row row-deck row-cards mt-2">
                <!-- Page Header Close -->
                <div class="col-12 m-0">
                    <div class="card" style="border-top: none">
                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $i++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>TK {{ number_format($item->balance, 2) }}</td>
                                                <td>
                                                    @can('suppliers.status')
                                                        @if ($item->status == 0)
                                                            <a href="{{ route('admin.supplier.status', ['id' => $item->id]) }}"
                                                                onclick="return confirm ('Are you sure do you want to Unblocked this?')"
                                                                class="badge bg-danger">
                                                                Block
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.supplier.status', ['id' => $item->id]) }}"
                                                                onclick="return confirm ('Are you sure do you want to Block this?')"
                                                                class="badge bg-success">
                                                                Unblocked
                                                            </a>
                                                        @endif
                                                    @endcan
                                                </td>
                                                <td class="w-1">
                                                    @can('suppliers.edit')
                                                        <a href="javascript:void(0);"
                                                            class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit-btn"
                                                            data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                            data-email="{{ $item->email }}" data-phone="{{ $item->phone }}"
                                                            data-balance="{{ $item->balance }}"
                                                            data-status="{{ $item->status }}">
                                                            <i class="ti ti-edit"></i>Edit
                                                        </a>
                                                    @endcan
                                                    @can('suppliers.delete')
                                                        <a href="{{ route('admin.supplier.delete', $item->id) }}"
                                                            class="btn-gradient-danger border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                            onclick="return confirm('Are you sure do delete this?')"><i
                                                                class="ti ti-trash"></i>Delete</a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-danger font-weight-bold">No Media
                                                Found!</td>
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
    <div class="modal modal-blur fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog  modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.supplier.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control mt-1" id="email" name="email">
                        </div>
                        <div class="form-group
                            mb-3">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="phone" name="phone" required>
                        </div>
                        <div class="form-group
                            mb-3">
                            <label for="balance">Balance </label>
                            <input type="number" class="form-control mt-1" id="balance" name="balance">
                        </div>
                        <div class="form-group
                            mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control mt-1">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success float-end">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal modal-blur fade" id="edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.supplier.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="id_e" name="id">
                        <div class="form-group
                            mb-3">
                            <label for="name">name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="name_e" name="name" required>
                        </div>
                        <div class="form-group
                            mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control mt-1" id="email_e" name="email">
                        </div>
                        <div class="form-group
                            mb-3">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control mt-1" id="phone_e" name="phone" required>
                        </div>
                        <div class="form-group
                            mb-3">
                            <label for="balance">Balance</label>
                            <input type="number" class="form-control mt-1" id="balance_e" name="balance">
                        </div>
                        <div class="form-group
                            mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status_e" class="form-control mt-1">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success float-end">Update</button>
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
            $('#id_e').val($(this).data('id'));
            $('#name_e').val($(this).data('name'));
            $('#email_e').val($(this).data('email'));
            $('#phone_e').val($(this).data('phone'));
            $('#balance_e').val(parseFloat($(this).data('balance')).toFixed(2));
            $('#status_e').val($(this).data('status'));
        });

        $(document).on('click', '.add_btn', function() {
            $('#add_modal').modal('show');
        });
    </script>
@endpush
