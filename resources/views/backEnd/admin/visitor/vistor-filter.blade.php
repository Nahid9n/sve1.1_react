@extends('backEnd.admin.layouts.master')

@section('title')
    @if ($status == 'unique')
        Unique Visitors
    @elseif ($status == 'total')
        Total Visitors
    @else
        Active Visitors
    @endif
@endsection
@section('content')
    {{-- @dd($data, $status) --}}
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                @if ($status == 'unique')
                <div class="col-6 d-flex justify-content-between align-items-center mb-2">
                        <h3 class="m-0"> Unique Visitors</h3>
                    @elseif ($status == 'total')
                    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                        <h3 class="m-0"> Total Visitors</h3>
                    @else
                    <div class="col-6 d-flex justify-content-between align-items-center mb-2">
                        <h3 class="m-0"> Active Visitors</h3>
                    @endif
                    <small class="float-end">
                        <a href="{{ route('admin.visitor.index') }}" class="btn btn-dark btn-sm">
                            <i class="ti ti-arrow-left"></i>
                            Back
                        </a>
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @if ($status == 'unique')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table align-top card-table datatabl">
                                            <thead>
                                                <tr style="vertical-align: middle;">
                                                    <th>SL</th>
                                                    <th>Url</th>
                                                    <th style="width:5%;text-align:center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($data) > 0)
                                                    @php($i = 1)
                                                    @foreach ($data as $key => $item)
                                                        <tr>
                                                            <td width="1%">{{ $i++ }}</td>
                                                            <td>{{ $item->ip }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.visitor.unique.list', ['ip' => $item->ip]) }}"
                                                                    class="btn btn-primary btn-sm">
                                                                    <i class="ti ti-eye"></i>
                                                                    &nbsp;View
                                                                </a>

                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="10" class="text-center"> <span
                                                                class="text-danger"><b>No
                                                                    data
                                                                    found.</b></span></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <div class="w-100 float-end p-1">
                                            {{ $data->links('backEnd.admin.includes.paginate') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($status == 'total')
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table align-top card-table datatabl">
                                    <thead>
                                        <tr style="vertical-align: middle;">
                                            <th>SL</th>
                                            <th>Url <br> Referer</th>
                                            <th>
                                                IP <br>
                                                Device <br>
                                                Platform <br>
                                                Browser
                                            </th>
                                            <th width="15%">Created <br> Updated</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($data) > 0)
                                            @foreach ($data as $key => $item)
                                                <tr>
                                                    <td width="1%">{{ $key + 1 }}</td>
                                                    <td>
                                                        <b>Url:</b>
                                                        {{ $item->url ? $item->url : 'N/A' }}
                                                        <br>
                                                        <b>Referrer:</b>
                                                        {{ $item->referer ? $item->referer : 'N/A' }}

                                                    <td>
                                                        {{ $item->ip ? $item->ip : 'N/A' }} <br>
                                                        {{ $item->device ? $item->device : 'N/A' }} <br>
                                                        {{ $item->platform ? $item->platform : 'N/A' }} <br>
                                                        {{ $item->browser ? $item->browser : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <small>
                                                            {{ date('d M Y h:i:s a', strtotime($item->created_at)) }}<br>
                                                            {{ date('d M Y h:i:s a', strtotime($item->updated_at)) }}
                                                        </small>
                                                    </td>
                                                    {{-- <td>
                                                        <div class="d-flex">
                                                            <form action="{{ route('admin.visitor.delete', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                    onclick="return confirm('Are you sure want to delete this?')"><i
                                                                        class="ti ti-trash"></i>
                                                                    &nbsp;Delete</button>
                                                            </form>
                                                        </div>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center"> <span class="text-danger"><b>No
                                                            data
                                                            found.</b></span></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <div class="w-100 float-end p-1">
                                    {{ $data->links('backEnd.admin.includes.paginate') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table align-top card-table datatabl">
                                            <thead>
                                                <tr style="vertical-align: middle;">
                                                    <th>SL</th>
                                                    <th>IP</th>
                                                    <th>Url</th>
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($data) > 0)
                                                    @foreach ($data as $key => $item)
                                                        <tr>
                                                            <td width="1%">{{ $key + 1 }}</td>
                                                            <td>{{ $item->ip }}</td>
                                                            <td>{{ $item->url }}</td>
                                                            {{-- <td>
                                                                <div class="d-flex">
                                                            <form action="{{ route('admin.visitor.delete', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                    onclick="return confirm('Are you sure want to delete this?')"><i
                                                                        class="ti ti-trash"></i>
                                                                    &nbsp;Delete</button>
                                                            </form>
                                                        </div>
                                                            </td> --}}
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="10" class="text-center"> <span
                                                                class="text-danger"><b>No
                                                                    data
                                                                    found.</b></span></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <div class="w-100 float-end p-1">
                                            {{ $data->links('backEnd.admin.includes.paginate') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // $(document).ready(function() {
        //     $('.page-link').on('click', function(e) {
        //         e.preventDefault();
        //         var dateRange = $('#dateRange').val();
        //         var url = $(this).attr('href');
        //         alert(url);
        //         $.ajax({
        //             url: url,
        //             type: 'GET',
        //             success: function(data) {
        //                 $('.datatabl').html(data);
        //             }
        //         });
        //     });
        // });

    </script>

@endpush
