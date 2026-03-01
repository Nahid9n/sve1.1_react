@extends('backEnd.admin.layouts.master')

@section('title')
    Review
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
                    <h3 class="m-0">
                        Review List
                    </h3>

                </div>
            </div>
            {{-- <div class="row ">
                <div class="col-12 m-0">
                    <a href="javascript:void(0);" class="btn btn-success btn-sm add_btn add_customer">
                        <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                        Add Customer
                    </a>
                </div>
            </div> --}}
            <div class="row row-deck row-cards mt-2">
                <div class="col-12 m-0">
                    <div class="card" style="border-top: none">
                        <div class="table-responsive">
                            <table class="table align-top card-table datatable">
                                <thead>
                                    <tr>
                                        <th style="width: 1%">SL.</th>
                                        <th>Customer Name</th>
                                        <th>Product Name</th>
                                        <th>Ratting</th>
                                        <th>Review</th>
                                        <th style="width: 1%">Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if ($data->count() > 0)
                                        @foreach ($data as $item)
                                            <tr @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>

                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->get_product ? $item->get_product->name : 'N/A' }}</td>
                                                <td>{{ $item->rating }}</td>
                                                <td>{{ $item->review }}</td>
                                                <td>
                                                    @can('newsletter.delete')
                                                        <form action="{{ route('admin.review.destroy', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                onclick="return confirm('Are you sure want to delete this?')">
                                                                <i class="ti ti-trash"></i>
                                                                Delete</button>
                                                        </form>
                                                    @endcan
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
@endsection
