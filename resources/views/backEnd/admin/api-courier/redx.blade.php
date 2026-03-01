@extends('backEnd.admin.layouts.master')

@section('title')
    Redx API
@endsection

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <!-- ============================================================== -->
                <!-- pageheader  -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h3 class="pageheader-title">Redx API</h3>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <div class="row">
                    <!-- ============================================================== -->
                    <div class="col-md-6 col-12">
                        <form action="{{ route('admin.redx.api.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="base_url">Base URL</label>
                                        <input type="text" class="form-control" value="{{ $data->base_url ?? null }}"
                                            name="base_url" id="base_url">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" class="form-control" value="{{ $data->username ?? null }}"
                                            name="username" id="username">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="text" class="form-control" value="{{ $data->password ?? null }}"
                                            name="password" id="password">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="api_token">API Token</label>
                                        <textarea class="form-control form-control-textarea" name="api_token" id="api_token">{{ $data->config['api_token'] ?? null }}</textarea>
                                    </div>
                                    <div class="col-12 mb-3 d-flex align-items-center flex-row-reverse justify-content-end">
                                        <label class="mx-2" for="status">Are you sure to active this
                                            API?</label>
                                        <input type="checkbox" name="status" id="status" value="1" class=""
                                            style="width: 15px; height: 15px;"
                                            @if (!empty($data->status)) checked @endif>
                                    </div>
                                    <button type="submit" class="btn btn-success float-end mt-2">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
