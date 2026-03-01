@extends('backEnd.admin.layouts.master')
@section('title')
    Expenses
@endsection
@php
    $setting = DB::table('web_settings')->select('currency_sign')->where('id', 1)->first();
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>Expenses</h3>
                </div>
                <div class="col-12">
                    @can('expenses.create')
                        <a href="javascript:void(0);" class="btn btn-success btn-sm add_expense">
                            <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                            Add Expense</a>
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
                                        <th>SL</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Note</th>
                                        <th style="width: 5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($data) > 0)
                                        @php($i = 1)
                                        @foreach ($data as $key => $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $key + 1 }}</td>
                                                <td>{{ $item->get_category->name }}</td>
                                                <td>{{ $setting->currency_sign }}{{ number_format($item->amount, 2) }}</td>
                                                <td>{{ $item->get_transaction ? $item->get_transaction->purpose : '---' }}
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @can('expenses.edit')
                                                            <a href="javascript:void(0);"
                                                                class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1 edit_expense"
                                                                data-id="{{ $item->id }}"
                                                                data-category_id="{{ $item->category_id }}"
                                                                data-account_id="{{ $item->get_transaction ? $item->get_transaction->account_id : '' }}"
                                                                data-amount="{{ $item->amount }}"
                                                                data-purpose="{{ $item->get_transaction ? $item->get_transaction->purpose : '' }}">
                                                                <i class="ti ti-edit"></i>Edit
                                                            </a>
                                                        @endcan
                                                        @can('expenses.delete')
                                                            <form action="{{ route('admin.expense.delete', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                    onclick="return confirm('Are you sure want to delete this?')"><i
                                                                        class="ti ti-trash"></i>Delete</button>
                                                            </form>
                                                        @endcan
                                                    </div>
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
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="card-header">
                    <h5 class="modal-title">Add Expense</h5>
                    <button type="button" class="btn-close btn-sm" style="width: 40px;height:40px" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.expense.store') }}" method="POST">
                        @csrf
                        <div class="col-12 mb-3">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" required>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Account <span class="text-danger">*</span></label>
                            <select name="account_id" class="form-select" required>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->account_type == 1 ? 'Bank - ' . $account->bank_account_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : ($account->account_type == 2 ? 'Bkash - ' . $account->bkash_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : ($account->account_type == 3 ? 'Nagad - ' . $account->nagad_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : 'Rocket - ' . $account->rocket_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" value="0" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Purpose</label>
                            <textarea name="purpose" cols="5" rows="3" class="form-control"></textarea>
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
                    <form action="{{ route('admin.expense.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="col-12 mb-3">
                            <label class="form-label">Select Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select" id="category_id" required>
                                @foreach ($categories as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Account <span class="text-danger">*</span></label>
                            <select name="account_id" id="account_id" class="form-select" required>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ $account->account_type == 1 ? 'Bank - ' . $account->bank_account_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : ($account->account_type == 2 ? 'Bkash - ' . $account->bkash_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : ($account->account_type == 3 ? 'Nagad - ' . $account->nagad_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')' : 'Rocket - ' . $account->rocket_no . '(' . $setting->currency_sign . number_format($account->balance, 2) . ')')) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" id="amount" name="amount" value="0" class="form-control"
                                required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Purpose</label>
                            <textarea name="purpose" id="purpose" cols="5" rows="3" class="form-control"></textarea>
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
        $(document).on('click', '.add_expense', function() {
            $('#add_modal').modal('show');
        });

        $(document).on('click', '.edit_expense', function() {
            $('#edit_modal').modal('show');
            $('#id').val($(this).data('id'));
            $('#category_id').val($(this).data('category_id'));
            $('#account_id').val($(this).data('account_id'));
            $('#amount').val($(this).data('amount'));
            $('#purpose').val($(this).data('purpose'));
        });
    </script>
@endpush
