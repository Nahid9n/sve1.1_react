@php
    $flags = $item->extra_fields ?? [];
    $targetKeys = ['is_top_category'];
@endphp
{{-- Add Category Modal --}}
<div class="modal fade" id="add_cat_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="addCategoryForm" action="{{ route('admin.category.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" id="cat_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Category Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="cat_slug" class="form-control" required>
                        <small id="slug_msg" class="fw-bold"></small>
                    </div>
                    <div class="mb-3">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Is Show Home <span class="text-danger">*</span></label>
                        <select name="is_show_home" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    @foreach ($targetKeys as $key)
                        <div class="mb-3">
                            <label>{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                            <select name="extra_fields[{{ $key }}]" id="{{ $key }}_add"
                                class="form-select">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                    @endforeach

                    <div class="mb-3">
                        <label>Image</label> <br>
                        <input type="file" name="file_url" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success float-end cat_btn">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Add Sub Category Modal --}}
<div class="modal fade" id="add_sub_cat_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form id="addSubCategoryForm" action="{{ route('admin.category.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="parent_id" id="sub_parent">
                    <div class="mb-3">
                        <label>Parent Category</label>
                        <input type="text" id="parent_name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Sub Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" id="sub_cat_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" id="sub_cat_slug" class="form-control" required>
                        <small id="sub_slug_msg"></small>
                    </div>
                    <div class="mb-3">
                        <label>Image</label> <br>
                        <input type="file" name="file_url" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Is Show Home <span class="text-danger">*</span></label>
                        <select name="is_show_home" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    @foreach ($targetKeys as $key)
                        <div class="mb-3">
                            <label>{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                            <select name="extra_fields[{{ $key }}]" id="{{ $key }}_sub_add"
                                class="form-select">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                    @endforeach
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success float-end cat_btn">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Sub Category Modal --}}
<div class="modal fade" id="edit_sub_cat_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <form id="editSubCategoryForm" action="{{ route('admin.category.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="parent_id" id="sub_parent_e">
                    <input type="hidden" name="id" id="category_id_e">
                    <div class="mb-3">
                        <label>Sub Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" id="sub_category_name_e" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Slug <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" name="slug" id="sub_category_slug_e" class="form-control"
                                required>
                            <button type="button" class="btn btn-primary px-3" id="saveSubSlugBtn">
                                <i class="ti ti-check"></i>
                            </button>
                        </div>
                        <small id="sub_slug_msg_e"></small>
                    </div>
                    <div class="mb-3">
                        <label>Image</label> <br>
                        <img id="img_append_sub" src="" class="img-thumbnail mb-2" style="width:100px;">
                        <input type="file" name="file_url" class="form-control">
                        <input type="hidden" id="file_url_old_sub" name="file_url_old">
                    </div>
                    <div class="mb-3">
                        <label>Is Show Home </label>
                        <select name="is_show_home" id="is_show_home_e" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    @foreach ($targetKeys as $key)
                        <div class="mb-3">
                            <label>{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                            <select name="extra_fields[{{ $key }}]" id="{{ $key }}_sub_edit"
                                class="form-select">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                    @endforeach
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="status_sub_e" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success float-end cat_btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Category Modal --}}
<div class="modal fade" id="edit_cat_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editCategoryForm" action="{{ route('admin.category.update') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="category_id_edit">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" id="category_name_edit" class="form-control"
                            required>
                    </div>
                    <div class="mb-3">
                        <label>Category Slug <span class="text-danger">*</span></label>
                        <div class="input-group category-slug-section">
                            <input type="text" name="slug" id="category_slug_edit" class="form-control"
                                required>
                            <button type="button" class="btn btn-primary px-3" id="saveBtn">
                                <i class="ti ti-check"></i>
                            </button>
                        </div>
                        <small id="slug_msg_edit"></small>
                    </div>
                    <div class="mb-3">
                        <label>Image</label> <br>
                        <img id="img_append" src="" class="img-thumbnail mb-2" style="width:100px;">
                        <input type="file" name="file_url" class="form-control">
                        <input type="hidden" id="file_url_old" name="file_url_old">
                    </div>
                    <div class="mb-3">
                        <label>Is Show Home</label>
                        <select name="is_show_home" id="is_show_home_edit" class="form-select">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    @foreach ($targetKeys as $key)
                        <div class="mb-3">
                            <label>{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                            <select name="extra_fields[{{ $key }}]" id="{{ $key }}_edit"
                                class="form-select">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                    @endforeach
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" id="status_edit" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success float-end cat_btn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
