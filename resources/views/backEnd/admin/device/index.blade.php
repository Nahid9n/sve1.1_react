@extends('backEnd.admin.layouts.master')

@section('title')
    Devices
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
                    <h3>
                        Devices
                    </h3>

                </div>
            </div>
            <div class="row ">
                {{-- <div class="col-12 m-0">
                    <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn add_customer">
                        <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                        Add Customer
                    </a>
                </div> --}}
            </div>
            <div class="row row-deck row-cards mt-2">
                <div class="col-12 m-0">
                    <div class="card" style="border-top: none">
                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>
                        <div class="table-responsive">
                            <table class="table align-top card-table datatable">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Customer Infos</th>
                                        <th>Device Infos</th>
                                        <th>Total Order</th>
                                        <th width="10%">Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            {{-- @dd($item) --}}
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $i++ }}</td>
                                                <td>
                                                    <span><strong>Name:</strong> {{ $item->user?->name }}</span> <br>
                                                    <span><strong>Email:</strong> {{ $item->user?->email }}</span> <br>
                                                    <span><strong>Phone:</strong> {{ $item->user?->phone }}</span> <br>
                                                    <span><strong>Addres:</strong> {{ $item->user?->address }}</span>
                                                </td>
                                                <td>
                                                    <span><strong>Device Name:</strong> {{ $item->device_name }}</span> <br>
                                                    <span><strong>Device Id:</strong> {{ $item->device_id }}</span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $total_order = $item->user?->get_orders->count();
                                                    ?>
                                                    <span class="badge bg-success me-2">{{ $total_order }}</span>
                                                    {{-- @if ($total_order > 0)
                                                        <a class="btn btn-outline-success btn-sm order_view"
                                                            data-id="{{ $item->id }}">View</a>
                                                    @endif --}}

                                                </td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.customer.device.status', [$item->id, 0]) }}"
                                                            onclick="return confirm('Are you sure want to change this status?')"><span
                                                                class="badge bg-success">Unblocked</span></a>
                                                    @else
                                                        <a href="{{ route('admin.customer.device.status', [$item->id, 1]) }}"
                                                            onclick="return confirm('Are you sure want to change this status?')"><span
                                                                class="badge bg-danger">Blocked</span></a>
                                                    @endif
                                                </td>
                                                <td class="w-1">
                                                    {{-- <a href="javascript:void(0);"
                                                        class="btn btn-outline-success btn-sm mb-1 w-100 edit_customer"
                                                        data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                                        data-email="{{ $item->email }}" data-phone="{{ $item->phone }}"
                                                        data-status="{{ $item->status }}">
                                                        <i class="ti ti-edit"></i>
                                                        Edit --}}
                                                    </a>
                                                    <form action="{{ route('admin.customer.delete', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                            onclick="return confirm('Are you sure want to delete this?')">
                                                            <i class="ti ti-trash"></i>
                                                            Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-danger font-weight-bold">No Data
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
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Customer</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.customer.store') }}" method="POST">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="number" name="phone" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-sm">Save</button>
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
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.customer.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="col-12 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="number" name="phone" id="phone" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Order View Modal -->
    <div class="modal fade" id="order_view_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl " role="document">
            <div class="modal-content">
                <div class="card-header p-3">
                    <h5 class="modal-title">Order View</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="card">

                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@push('js')
    <script>
        $(document).on('click', '.add_customer', function() {
            $('#add_modal').modal('show');
        });
        $(document).on('click', '.order_view', function() {
            $('#order_view_modal').modal('show');
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('admin.customer.order') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(data) {
                    $('#order_view_modal .modal-body .card').html(data);

                }
            });

        });

        $(document).on('click', '.edit_customer', function() {
            $('#edit_modal').modal('show');
            $('#id').val($(this).data('id'));
            $('#name').val($(this).data('name'));
            $('#email').val($(this).data('email'));
            $('#phone').val($(this).data('phone'));
            $('#status').val($(this).data('status'));
        });
    </script>
@endpush
