@extends('backEnd.admin.layouts.master')

@section('title')
    Page Settings
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3> Page Settings</h3>
                </div>
            </div>
            <div class="row">
                <form method="post" action="{{ route('admin.settings.page.update') }}" enctype="multipart/form-data"
                    id="add-page-settings-form">
                    @csrf
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-tabs" data-bs-toggle="tabs">
                                    <li class="nav-item">
                                        <a href="#tabs-about-us" class="nav-link active" data-bs-toggle="tab">About Us</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#delivery-policy" class="nav-link" data-bs-toggle="tab">Delivery Policy</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#return-policy" class="nav-link" data-bs-toggle="tab">Return Policy</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#privacy-policy" class="nav-link" data-bs-toggle="tab">Privacy Policy</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#term-condition" class="nav-link" data-bs-toggle="tab">Term & Condition</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#how-to-order" class="nav-link" data-bs-toggle="tab">How To Order</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#why-us" class="nav-link" data-bs-toggle="tab">Why Us</a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="#contact-us" class="nav-link" data-bs-toggle="tab">Contact Us</a>
                                    </li>


                                </ul>

                                <div class="tab-content">
                                    <form method="post" action="{{ route('admin.settings.page.update') }}"
                                        enctype="multipart/form-data" id="add-general-settings-page">
                                        @csrf
                                        <div class="tab-pane active show" id="tabs-about-us">
                                            <textarea name="about_us" id="" class="form-control summernote">{{ $data ? $data->about_us : '' }}</textarea>
                                        </div>
                                        <div class="tab-pane" id="delivery-policy">
                                            <textarea name="delivery_policy" id="" class="form-control summernote">{{ $data ? $data->delivery_policy : '' }}</textarea>
                                        </div>
                                        <div class="tab-pane" id="return-policy">
                                            <textarea name="return_policy" id="" class="form-control summernote">{{ $data ? $data->return_policy : '' }}</textarea>
                                        </div>
                                        <div class="tab-pane" id="privacy-policy">
                                            <textarea name="privacy_policy" id="" class="form-control summernote">{{ $data ? $data->privacy_policy : '' }}</textarea>
                                        </div>
                                        <div class="tab-pane" id="term-condition">
                                            <textarea name="terms_condition" id="" class="form-control summernote">{{ $data ? $data->terms_condition : '' }}</textarea>
                                        </div>

                                        <div class="tab-pane" id="how-to-order">
                                            <textarea name="how_to_order" id="" class="form-control summernote">{{ $data ? $data->how_to_order : '' }}</textarea>
                                        </div>

                                        <div class="tab-pane" id="why-us">
                                            <textarea name="why_us" id="" class="form-control summernote">{{ $data ? $data->why_us : '' }}</textarea>
                                        </div>

                                        <div class="tab-pane" id="contact-us">
                                            <textarea name="contact_us" id="" class="form-control summernote">{{ $data ? $data->contact_us : '' }}</textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success float-end submit-btn mt-3"
                                            data-status="1">Update</button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('backEnd/assets/libs/summernote/summernote-bs4.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                minHeight: 300,
            });
        });
    </script>
@endpush
