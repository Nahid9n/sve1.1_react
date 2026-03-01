@extends('backEnd.admin.layouts.master')
@section('title')
    IP Address
@endsection
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row d-flex justify-content-between align-items-center mb-2">
                <div class="col-8">
                    <h3 class="m-0"> IP Address</h3>
                </div>
                <div class="col-4">
                    <div class="search">
                        <form action="{{ route('admin.ip.address') }}"
                            class="d-flex flex-md-row flex-column align-items-end justify-content-end">
                            <input type="text" class="form-control form-control-sm small-search mb-md-0 mb-1 me-md-1"
                                name="query" aria-label="Search..." placeholder="Type Here..."
                                value="{{ request()->query('query') }}">
                            <div class="mb-md-0 mb-1 d-flex">
                                {{-- <button class="btn btn-info btn-sm me-1" type="submit">Search</button> --}}
                                <a href="{{ route('admin.ip.address') }}" class="btn reset_button btn-sm"><i
                                        class="ti ti-refresh"></i></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- Page Header Close -->
                <div class="col-md-12 col-12 mb-3">
                    <div class="card" style="border-top:none">
                        {{-- <form action="" id="date_range_picker_form" class="d-flex">
                        <input name="date_range" class="form-control form-control-sm" id="date_range_picker">
                        <button type="submit" class="date_filter_button btn btn-primary ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </button>
                    </form> --}}
                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>IP Address</th>
                                        <th>Total Order</th>
                                        <th>Status</th>
                                        <th style="width: 5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($data) > 0)
                                        @foreach ($data as $key => $item)
                                            <tr @if (($key + 1) % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td width="1%">{{ $key + 1 }}</td>
                                                <td>{{ $item->ip_address }}</td>
                                                <td>{{ $item->total_order }}</td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        <a href="{{ route('admin.ip.address.status', $item->id) }}"
                                                            onclick="return confirm('Are you sure want to block this?')"
                                                            class="badge bg-success">Unblocked</a>
                                                    @else
                                                        <a href="{{ route('admin.ip.address.status', $item->id) }}"
                                                            onclick="return confirm('Are you sure want to unblock this?')"
                                                            class="badge bg-danger">Blocked</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('ip_address.delete')
                                                            <form action="{{ route('admin.ip.address.delete', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                                                    onclick="return confirm('Are you sure want to delete this?')"><i
                                                                        class="ti ti-trash"></i>
                                                                    Delete</button>
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

                {{-- <div class="col-md-5 col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Url</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($top_urls) > 0)
                                        @foreach ($top_urls as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->url }}</td>
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
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
