@extends('backEnd.admin.layouts.master')
@section('title')
    Coupons
@endsection
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>
                        Coupon List
                    </h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="action-btn d-flex gap-2">
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-success btn-sm">
                            <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>Add Coupon
                        </a>
                    </div>

                </div>
            </div>
            <div class="row row-deck row-cards mt-2 product-index">
                <div class="col-12 m-0">
                    <div class="card" style="border-top: none">
                        <div class="table-responsive order_table">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th><input class="form-check-input m-0 align-middle" type="checkbox" id="selectAll">
                                        </th>
                                        <th>SL.</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Max Discount</th>
                                        <th>Min Purchase</th>
                                        <th>Apply On</th>
                                        <th>Usage</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($coupons->count() > 0)
                                        @foreach ($coupons as $coupon)
                                            <tr class="coupon-item{{ $coupon->id }}"
                                                @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td class="w-1"><input class="form-check-input m-0 align-middle sub_chk"
                                                        type="checkbox" data-id="{{ $coupon->id }}"></td>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $coupon->code }}</td>
                                                <td>{{ ucfirst($coupon->type) }}</td>
                                                <td>{{ $coupon->amount }}</td>
                                                <td>{{ $coupon->max_discount ?? '-' }}</td>
                                                <td>{{ $coupon->min_purchase }}</td>
                                                <td>{{ ucfirst($coupon->apply_on) }}</td>
                                                <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                                                <td>
                                                    <form action="{{ route('admin.coupons.toggle', $coupon->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm {{ $coupon->status ? 'btn-success' : 'btn-secondary' }}">
                                                            {{ $coupon->status ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="w-1">
                                                    <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                                        class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                        <i class="ti ti-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                            <i class="ti ti-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-center text-danger font-weight-bold">No Coupons
                                                Found!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{-- <div class="mt-2">
                                {{ $coupons->links('backEnd.admin.includes.paginate') }}
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#selectAll').on('change', function() {
                $(".sub_chk").prop('checked', $(this).is(':checked'));
            });

            $('#bulk_delete_btn').on('click', function() {
                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).data('id'));
                });
                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    if (confirm("Are you sure you want to delete?")) {
                        $('.all_delete_id').val(allVals);
                        $('#all_delete_form').submit();
                    }
                }
            });
        });
    </script>
@endpush
