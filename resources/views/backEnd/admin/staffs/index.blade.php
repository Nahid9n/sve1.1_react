@extends('backEnd.admin.layouts.master')
@section('title')
    Staffs
@endsection
@section('content')

    <div class="container-xl">
        <!-- Page title -->
        <div class="mt-3 d-print-none">
            <div class="row">
                <div class="col-12">
                    <h3>Staffs</h3>

                    <div class="col-12">
                        @can('staffs.create')
                            <a href="javascript:void(0)" class="btn btn-success add-btn btn-sm">
                                <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                                Add Staff
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table ">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Role</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Joined</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($i = 1)
                                        @if (count($staffs) > 0)
                                            @foreach ($staffs as $item)
                                                <tr
                                                    @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                    <td width="1%">{{ $i++ }}</td>
                                                    <td class="text-capitalize">
                                                        @if ($item->getRoleNames())
                                                            @foreach ($item->getRoleNames() as $rolename)
                                                                <label class="mx-1">{{ $rolename }}</label>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->phone }}</td>
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ date('d M, Y', strtotime($item->created_at)) }}<br>
                                                        {{ date('h:i:s A', strtotime($item->created_at)) }}
                                                    </td>
                                                    <td>
                                                        @can('staffs.status')
                                                            @if ($item->role_id != 1)
                                                                @if ($item->status == 1)
                                                                    <a onclick="return confirm('Are You Sure?')"
                                                                        href="{{ route('admin.staff.status', [$item->id, 0]) }}"><span
                                                                            class="badge bg-success">Active</span></a>
                                                                @else
                                                                    <a onclick="return confirm('Are You Sure?')"
                                                                        href="{{ route('admin.staff.status', [$item->id, 1]) }}"><span
                                                                            class="badge bg-danger">Inactive</span></a>
                                                                @endif
                                                            @endif
                                                        @endcan
                                                    </td>
                                                    <td class="w-1">
                                                        @can('staffs.edit')
                                                            <a href="javascript:void(0)"
                                                                class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit-btn"
                                                                data-id="{{ $item->id }}"
                                                                data-role_id="{{ $item->role_id }}"
                                                                data-name="{{ $item->name }}"
                                                                data-phone="{{ $item->phone }}"
                                                                data-email="{{ $item->email }}"
                                                                data-status="{{ $item->status }}"
                                                                data-theme_id="{{ $item->theme_id }}"
                                                                data-is_order_assign="{{ $item->is_order_assign }}"><i
                                                                    class="ti ti-edit"></i>Edit</a>
                                                        @endcan
                                                        <br>
                                                        {{-- <a href="{{ route('admin.staff.delete', $item->id) }}"
                                                        onclick="return confirm('Are You Sure?')"
                                                        class="btn btn-danger border-0 btn-sm w-100 d-flex justify-content-center gap-1"><i
                                                            class="ti ti-trash"></i> &nbsp;Delete</a> --}}
                                                        {{-- @endif --}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-danger text-center fw-bold">No Data Found !
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- <div>
                            {{ $categories->links('backEnd.pagination.custom_pagination') }}
                        </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- add modal --}}
    <div class="modal  fade" id="add-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Staff</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.staff.store') }}" method="post" id="add-form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label required" for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="email">Email</label>
                            <input type="text" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone">
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="role_id">Role</label>
                            <select name="role_id" id="role_id" class="form-select">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="theme_id">Theme</label>
                            <select name="theme_id" id="theme_id" class="form-select">
                                @foreach ($themes as $key => $theme)
                                    <option value="{{ $key }}">{{ $theme }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="status">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" name="is_order_assign" type="checkbox" value="1"
                                    id="order_assign">
                                <label class="form-check-label" for="order_assign">
                                    Is Order Assign
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success float-end">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- edit modal --}}
    <div class="modal  fade" id="edit-modal" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Edit Staff</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.staff.update') }}" method="post" id="edit-form"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="id_e" name="id">
                        <div class="mb-3">
                            <label class="form-label required" for="name_e">Name</label>
                            <input type="text" class="form-control" name="name" id="name_e" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="email_e">Email</label>
                            <input type="text" class="form-control" name="email" id="email_e" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phone_e">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone_e">
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="role_id_e">Role</label>
                            <select name="role_id" id="role_id_e" class="form-select">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="theme_id_e">Theme</label>
                            <select name="theme_id" id="theme_id_e" class="form-select">
                                @foreach ($themes as $key => $theme)
                                    <option value="{{ $key }}">{{ $theme }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required" for="status_e">Status</label>
                            <select name="status" id="status_e" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input order_assign_e" name="is_order_assign" type="checkbox"
                                    value="1" id="order_assign_e">
                                <label class="form-check-label" for="order_assign_e">
                                    Is Order Assign
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
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
        $(document).ready(function() {
            $('.add-btn').click(function() {
                $('#add-modal').modal('show');
            });

            $('.edit-btn').click(function() {
                $('#id_e').val($(this).data('id'));
                $('#name_e').val($(this).data('name'));
                $('#email_e').val($(this).data('email'));
                $('#phone_e').val($(this).data('phone'));
                $('#role_id_e').val($(this).data('role_id'));
                $('#theme_id_e').val($(this).data('theme_id'));
                $('#status_e').val($(this).data('status'));
                // $('.order_assign_e').val($(this).data('is_order_assign'));
                if ($(this).data('is_order_assign') == 1) {
                    $('.order_assign_e').prop('checked', true);
                } else {
                    $('.order_assign_e').prop('checked', false);
                }
                $('#edit-modal').modal('show');
            });
        });
    </script>
@endpush
