@extends('backEnd.admin.layouts.master')

@section('title')
    Carrybee API
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
                            <h3 class="pageheader-title">Carrybee API</h3>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <div class="row">
                    <!-- ============================================================== -->
                    <div class="col-md-6 col-12">
                        <form action="{{ route('admin.carrybee.api.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="base_url">Base URL</label>
                                        <input type="text" class="form-control" value="{{ $data->base_url ?? null }}"
                                            name="base_url" id="base_url">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="store_id">Store ID</label>
                                        <input type="text" class="form-control"
                                            value="{{ $data->config['store_id'] ?? null }}" name="store_id" id="store_id">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="client_id">Client ID</label>
                                        <input type="text" class="form-control"
                                            value="{{ $data->config['client_id'] ?? null }}" name="client_id"
                                            id="client_id">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="client_secret">Client Secret</label>
                                        <input type="text" class="form-control"
                                            value="{{ $data->config['client_secret'] ?? null }}" name="client_secret"
                                            id="client_secret">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="client_context">Client Context</label>
                                        <input type="text" class="form-control"
                                            value="{{ $data->config['client_context'] ?? null }}" name="client_context"
                                            id="client_context">
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
