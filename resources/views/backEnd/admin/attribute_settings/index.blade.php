@extends('backEnd.admin.layouts.master')

@section('title')
    Attribute Settings
@endsection

@section('content')

    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn">Add Attribute</a>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <!-- Page Header Close -->
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>Title</th>
                                        <th>Variant Item(s)</th>
                                        <th>Is Image?</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    @if ($item->items->count() > 0)
                                                        @foreach ($item->items as $key => $value)
                                                            <a href="javascript:void(0)" class="edit_item_btn"
                                                                data-attribute_item_id="{{ $value->id }}"
                                                                data-attribute_item_title="{{ $value->name }}">
                                                                <i class="ti ti-edit"></i>
                                                            </a>

                                                            <a href="{{ route('admin.settings.attribute_item.delete', $value->id) }}"
                                                                onclick="return confirm('Are you sure to delete this?')"
                                                                class="mr-1">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                                            <span>{{ $value->name }}</span>
                                                            <br>
                                                        @endforeach
                                                    @endif

                                                    <a href="javascript:void(0)" class="add_item_btn badge bg-primary"
                                                        data-attribute_id="{{ $item->id }}"
                                                        data-attribute_title="{{ $item->name }}">
                                                        Add
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($item->is_image == 1)
                                                        <a href="javascript:void(0)" class="badge bg-success default_btn"
                                                            data-id="{{ $item->id }}">
                                                            Yes
                                                        </a>
                                                    @else
                                                        <a href="javascript:void(0)" class="badge bg-danger default_btn"
                                                            data-id="{{ $item->id }}">
                                                            No
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="w-1">
                                                    <a href="javascript:void(0)"
                                                        class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit_btn"
                                                        data-id="{{ $item->id }}" data-title="{{ $item->name }}"
                                                        data-is_image="{{ $item->is_image }}"
                                                        data-status="{{ $item->status }}">
                                                        <i class="ti ti-edit"></i>Edit
                                                    </a>
                                                    <a href="{{ route('admin.settings.attribute.delete', $item->id) }}"
                                                        onclick="return confirm('Are you sure to delete this?')"
                                                        class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"><i
                                                            class="ti ti-trash"></i>Delete</a>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal modal-blur fade" id="add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Attribute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.settings.attribute.store') }}" method="post">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="name" required>
                        </div>

                        <div class="col-12 mb-3">
                            <input type="checkbox" id="is_image" name="is_image" value="1">
                            <label for="is_image">Is Image</label>
                        </div>

                        <div class="col-12 mb-3">
                            <button type="submit" class="btn btn-success float-end mt-2">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal modal-blur fade" id="edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Attribute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.settings.attribute.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id_e">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="title_e">Title</label>
                            <input type="text" class="form-control" id="title_e" name="name" required>
                        </div>
                        <div class="col-12 mb-3">
                            <input type="checkbox" id="is_image_e" name="is_image" value="1">
                            <label for="is_image_e">Is Image</label>
                        </div>

                        <div class="col-12 mb-3">
                            <button type="submit" class="btn btn-success float-end mt-2">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Attribute Modal -->
    <div class="modal modal-blur fade" id="item_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Attribute Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.settings.attribute_item.store') }}" method="post">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label" for="attribute_id">Attribute Title</label>
                            <select name="attribute_id" id="attribute_id" class="form-control"></select>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label" for="item_title">Attribute Item Title</label>
                            <input type="text" class="form-control" id="item_title" name="name" required>
                        </div>

                        <div class="col-12 mb-3">
                            <input type="submit" class="btn btn-success btn-sm" value="Add">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Attribute Modal -->
    <div class="modal modal-blur fade" id="item_edit_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Attribute Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.settings.attribute_item.update') }}" method="post">
                        @csrf
                        <input type="hidden" id="attribute_item_id_e" name="id">
                        <div class="col-12 mb-3">
                            <label class="form-label" for="item_title_e">Attribute Item Title</label>
                            <input type="text" class="form-control" id="item_title_e" name="name" required>
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
        $(document).on('click', '.add_btn', function() {
            $('#add_modal').modal('show');
        });

        $(document).on('click', '.edit_btn', function() {
            $('#edit_modal').modal('show');
            $('#id_e').val($(this).data('id'));
            $('#title_e').val($(this).data('title'));
            if ($(this).data('is_image') == 1) {
                $('#is_image_e').prop('checked', true);
            } else {
                $('#is_image_e').prop('checked', false);
            }
            $('#status_e').val($(this).data('status'));
        });

        $(document).on('click', '.add_item_btn', function() {
            $('#attribute_id').append('<option value="' + $(this).data('attribute_id') + '">' + $(this).data(
                'attribute_title') + '</option>');
            $('#item_add_modal').modal('show');
        });

        $(document).on('click', '.edit_item_btn', function() {
            $('#attribute_item_id_e').val($(this).data('attribute_item_id'));
            $('#item_title_e').val($(this).data('attribute_item_title'));
            $('#item_edit_modal').modal('show');
        });
    </script>
@endpush
