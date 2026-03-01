@extends('backEnd.admin.layouts.master')

@section('title')
    Unique Visitors List
@endsection
@section('content')
    {{-- @dd($data, $status) --}}
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                    <h3 class="m-0">Unique Visitors list</h3>
                    <small class="float-end">
                        <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm">
                            <i class="ti ti-arrow-left"></i>
                            Back
                        </a>
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table align-top card-table datatabl">
                                <thead>
                                    <tr style="vertical-align: middle;">
                                        <th>SL</th>
                                        <th>
                                            Url <br>
                                            Referrer
                                        </th>
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
                                        @php($i = 1)
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td width="1%">{{ $i++ }}</td>
                                                <td>
                                                    <b>Url:</b>
                                                    {{ $item->url ? $item->url : 'N/A' }}
                                                    <br>
                                                    <b>Referrer:</b>
                                                    {{ $item->referer ? $item->referer : 'N/A' }}

                                                </td>
                                                {{-- <td>{{ $item->referer }}</td> --}}
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


                </div>
            </div>
        </div>
    </div>
@endsection
