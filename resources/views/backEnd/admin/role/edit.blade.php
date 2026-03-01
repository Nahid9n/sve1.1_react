@extends('backEnd.admin.layouts.master')
@section('title', 'Edit Role & Permissions')

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <div class="row  mb-2 mt-3">
                    <div class="col-12">
                        <h3>
                            Edit Role & Permissions
                            <small class="float-end">
                                <a href="{{ route('admin.role.index') }}" class="btn btn-dark btn-sm">
                                    <i class="fa fa-angle-double-left"></i>
                                    <i class="ti ti-arrow-left"></i>
                                    Back
                                </a>
                            </small>
                        </h3>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                            @csrf

                            {{-- Role Name --}}
                            <div class="mb-3">
                                <label class="form-label">Role Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $role->name }}">
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Select All --}}
                            <div class="mb-3 d-flex align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="all_permissions_checked">
                                    <label class="form-check-label" for="all_permissions_checked">Select All
                                        Permissions</label>
                                </div>
                            </div>

                            {{-- Permissions --}}
                            <div class="row">
                                @foreach ($custom_permission as $group => $permissions)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border shadow-sm">
                                            <div class="card-header py-2 bg-light">
                                                <div class="form-check mb-0">
                                                    <input type="checkbox" class="form-check-input manage_check"
                                                        id="group_{{ $group }}"
                                                        {{ count($permissions->pluck('name')->intersect($rolePermissions)) == $permissions->count() ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold text-dark"
                                                        for="group_{{ $group }}">
                                                        {{ str_replace('_', ' ', ucfirst($group)) }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @foreach ($permissions as $index => $permission)
                                                    <div class="form-check form-check-inline mb-2">
                                                        <input type="checkbox" class="form-check-input child_input"
                                                            name="permissions[]" value="{{ $permission->name }}"
                                                            id="perm_{{ $group }}_{{ $index }}"
                                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="perm_{{ $group }}_{{ $index }}">
                                                            {{ collect(explode('.', str_replace('_', ' ', $permission->name)))->map(function ($item) {
                                                                    return ucfirst($item);
                                                                })->implode(' ') }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-success float-end">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function updateAllChecked() {
            let totalChildren = $('.child_input').length;
            let checkedChildren = $('.child_input:checked').length;
            $('#all_permissions_checked').prop('checked', totalChildren === checkedChildren);
        }

        $(document).on('click', '#all_permissions_checked', function() {
            let checked = $(this).is(':checked');
            $('.child_input, .manage_check').prop('checked', checked);
        });

        $(document).on('click', '.manage_check', function() {
            let checked = $(this).is(':checked');
            $(this).closest('.card').find('.child_input').prop('checked', checked);
            updateAllChecked();
        });

        $(document).on('click', '.child_input', function() {
            let parentCard = $(this).closest('.card');
            let allChild = parentCard.find('.child_input');
            let checkedChild = parentCard.find('.child_input:checked');
            parentCard.find('.manage_check').prop('checked', allChild.length === checkedChild.length);
            updateAllChecked();
        });

        $(document).ready(function() {
            updateAllChecked();
        });
    </script>
@endpush
