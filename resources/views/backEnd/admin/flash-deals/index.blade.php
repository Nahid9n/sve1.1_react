@extends('backEnd.admin.layouts.master')

@section('title')
    Flash Deals
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">

            <div class="row">
                <div class="col-12">
                    <h3>Flash Deal List</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="action-btn d-flex gap-2">
                        <a href="{{ route('admin.flash.deal.create') }}" class="btn btn-success btn-sm">
                            <i class="ti ti-plus me-1" style="margin-bottom:2px"></i> Add Flash Deal
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
                                        {{-- <th><input type="checkbox" class="form-check-input m-0" id="selectAll"></th> --}}
                                        <th>SL.</th>
                                        <th>Title</th>
                                        <th>Start</th>
                                        <th>End</th>
                                        <th>Status</th>
                                        <th>Products</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @php($i = 1)
                                    @if ($deals->count() > 0)
                                        @foreach ($deals as $deal)
                                            <tr class="deal-row{{ $deal->id }}"
                                                @if ($i % 2 == 0) style="background:#f5f5f5" @endif>

                                                {{-- <td><input type="checkbox" class="form-check-input sub_chk m-0"
                                                        data-id="{{ $deal->id }}"></td> --}}

                                                <td>{{ $i++ }}</td>

                                                <td>{{ $deal->title }}</td>

                                                <td>{{ $deal->start_time->format('d M Y — h:i A') }}</td>

                                                <td>{{ $deal->end_time->format('d M Y — h:i A') }}</td>

                                                <td>
                                                    <form action="{{ route('admin.flash.deal.toggle', $deal->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button
                                                            class="btn btn-sm {{ $deal->status ? 'btn-success' : 'btn-secondary' }}">
                                                            {{ $deal->status ? 'Active' : 'Inactive' }}
                                                        </button>
                                                    </form>
                                                </td>

                                                <td>{{ $deal->products->count() }}</td>

                                                <td class="w-1">
                                                    <a href="{{ route('admin.flash.deal.edit', $deal->id) }}"
                                                        class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                        <i class="ti ti-edit"></i> Edit
                                                    </a>

                                                    <form action="{{ route('admin.flash.deal.destroy', $deal->id) }}"
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
                                            <td colspan="8" class="text-center text-danger fw-bold">
                                                No Flash Deals Found!
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            {{-- {{ $deals->links('backEnd.admin.includes.paginate') }} --}}

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
@endpush
