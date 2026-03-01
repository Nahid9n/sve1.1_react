@extends('backEnd.admin.layouts.master')
@section('title')
    Role & Permissions
@endsection
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <!-- Page Header -->
                <div class="col-12">
                    <h3>Role & Permissions</h3>
                </div>
                <div class="col-12">
                    <h2 class="page-title">
                        @can('roles.create')
                            <a href="{{ route('admin.role.create') }}" class="btn btn-success btn-sm">
                                <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                                Add Roles & Permissions</a>
                        @endcan
                    </h2>
                </div>
            </div>
            <div class="row mt-2">
                <!-- Page Header Close -->
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <Th>Permissions</Th>
                                        <th style="width: 5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($roles->count() > 0)
                                        @foreach ($roles as $key => $role)
                                            <tr @if (($key + 1) % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $key + 1 }}</td>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    @foreach ($role->permissions as $key => $permission)
                                                        @php
                                                            $name = collect(
                                                                explode('.', str_replace('_', ' ', $permission->name)),
                                                            )
                                                                ->map(function ($item) {
                                                                    return ucfirst($item);
                                                                })
                                                                ->implode(' ');
                                                        @endphp
                                                        <span class="badge bg-blue-lt mb-2">{{ ucwords($name) }}</span>
                                                        @if (($key + 1) % 10 == 0)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @can('roles.edit')
                                                            <a href="{{ route('admin.role.edit', $role->id) }}"
                                                                class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                                <i class="ti ti-edit"></i>Edit
                                                            </a>
                                                        @endcan
                                                        @can('roles.delete')
                                                            <form action="{{ route('admin.role.delete', $role->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                @can('role_delete')
                                                                    <button type="submit"
                                                                        class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                        onclick="return confirm('Are you sure want to delete this?')"><i
                                                                            class="ti ti-trash"></i>
                                                                        Delete</button>
                                                                @endcan
                                                            </form>
                                                        @endcan
                                                    </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(document).on('click', '#all_permissions_checked', function() {
            if ($('#all_permissions_checked').is(':checked')) {
                $('.check-input').prop('checked', true);
            } else {
                $('.check-input').prop('checked', false);
            }
        });

        $(document).on('click', '.manage_check', function() {
            if ($(this).is(':checked')) {
                let parent = $(this).closest('.row');
                $(parent.find('.child_input')).each(function() {
                    $(this).prop('checked', true);
                });
            } else {
                let parent = $(this).closest('.row');
                $(parent.find('.child_input')).each(function() {
                    $(this).prop('checked', false);
                });
            }
        });
    </script>
@endpush
