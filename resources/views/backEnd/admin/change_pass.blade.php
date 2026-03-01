@extends('backEnd.admin.layouts.master')

@section('title')
    Change Password
@endsection

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <div class="row mt-3">
                    <div class="col-5">
                        <div class="card">
                            <h3 class="card-header">Change Password</h3>
                            <div class="card-body">
                                <form action="{{Auth::guard('admin')->check() ? route('admin.update_pass') : (Auth::guard('manager')->check() ? route('manager.update_pass') : (Auth::guard('employee')->check() ? route('employee.update_pass') : ""))}}" method="post">
                                    @csrf
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="old_pass">Old Password</label>
                                        <input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Enter Old Password" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="password">New Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
