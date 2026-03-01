@extends('backEnd.admin.layouts.master')
@section('title')
    Purchase
@endsection
@php
    $setting = DB::table('web_settings')->select('currency_sign')->where('id', 1)->first();
@endphp
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3>Purchase List</h3>
                </div>
                <div class="col-12">
                    @can('purchases.create')
                        <a href="{{ route('admin.purchase.create') }}" class="btn btn-success btn-sm add_supplier">
                            <i class="ti ti-plus me-1" style="margin-bottom: 2px"></i>
                            Add Purchase</a>
                    @endcan
                </div>
            </div>
            <div class="row row-deck row-cards mt-2">
                <!-- Page Header Close -->
                <div class="col-12 m-0">
                    <div class="card" style="border-top:none">
                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}

                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>Total</th>
                                        <th>Discount</th>
                                        <th>Due</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($data) > 0)
                                        @foreach ($data as $key => $item)
                                            <tr @if ($key + (1 % 2) == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $key + 1 }}</td>
                                                <td>
                                                    {{ date('d M Y', strtotime($item->purchase_date)) }}
                                                </td>

                                                <td>{{ $item->get_supplier?->name }}</td>
                                                <td>{{ $setting->currency_sign }}
                                                    {{ number_format($item->subtotal, 2, '.', '') }}</td>
                                                <td>{{ $setting->currency_sign }}
                                                    {{ number_format($item->discount, 2, '.', '') }}</td>
                                                <td>{{ $setting->currency_sign }}
                                                    {{ number_format($item->due_amount, 2, '.', '') }}</td>
                                                <td>
                                                    @if ($item->status == 0)
                                                        <span class="badge bg-warning dropdown-toggle"
                                                            data-bs-toggle="dropdown">Pending</span>

                                                        {{-- // dropdown
                                                        <div class="dropdown">
                                                            <button class="btn btn btn-cyan border-0 btn-sm d-flex justify-content-center gap-1 dropdown-toggle"
                                                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li><a class="dropdown-item"
                                                                        href="">Order</a>
                                                                </li>
                                                                <li><a class="dropdown-item"
                                                                        href="">Received</a>
                                                                </li>
                                                            </ul>
                                                        </div> --}}
                                                    @elseif($item->status == 1)
                                                        <span class="badge bg-info dropdown-toggle">Ordered</span>
                                                    @elseif($item->status == 2)
                                                        <span class="badge bg-success dropdown-toggle">Received</span>
                                                    @endif


                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                        <li><a class="dropdown-item {{ $item->status == 0 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.purchase.status', [$item->id, 'status' => 0]) }}">Pending</a>
                                                        </li>
                                                        <li><a class="dropdown-item  {{ $item->status == 1 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.purchase.status', [$item->id, 'status' => 1]) }}">Ordered</a>
                                                        </li>
                                                        <li><a class="dropdown-item  {{ $item->status == 2 ? 'd-none' : '' }}"
                                                                href="{{ route('admin.purchase.status', [$item->id, 'status' => 2]) }}">Received</a>
                                                        </li>

                                                    </ul>


                                                </td>
                                                <td class="w-1">
                                                    @can('purchases.edit')
                                                        <a href="{{ route('admin.purchase.edit', $item->id) }}"
                                                            class="btn-gradient-info  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                            <i class="ti ti-edit"></i> Edit
                                                        </a>
                                                    @endcan
                                                    <form action="{{ route('admin.purchase.delete', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        @can('purchases.delete')
                                                            <button type="submit"
                                                                class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                onclick="return confirm('Are you sure want to delete this?')"><i
                                                                    class="ti ti-trash"></i>
                                                                Delete</button>
                                                        @endcan
                                                    </form>
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

@endsection
