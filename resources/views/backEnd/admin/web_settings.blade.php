@extends('backEnd.admin.layouts.master')

@section('title')
    Web Settings
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/summernote/summernote-bs4.css') }}">
@endpush

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
                            <h3 class="pageheader-title">Web Settings</h3>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <!-- ============================================================== -->
                <form action="{{ route('admin.settings.web.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Website Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_address">Website Address</label>
                                        <textarea class="form-control form-control-textarea" name="website_address" id="website_address">{!! $data?->website_address ?? null !!}</textarea>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_phone">Website Phone</label>
                                        <input type="text" class="form-control" name="website_phone" id="website_phone"
                                            value="{{ $data?->website_phone ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_phone2">Website Phone 2</label>
                                        <input type="text" class="form-control" name="website_phone2" id="website_phone2"
                                            value="{{ $data?->website_phone2 ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_phone3">Website Phone 3</label>
                                        <input type="text" class="form-control" name="website_phone3" id="website_phone3"
                                            value="{{ $data?->website_phone3 ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_email">Website Email</label>

                                        <input type="email" class="form-control" name="website_email" id="website_email"
                                            value="{{ $data?->website_email ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_email">Website Email 2</label>

                                        <input type="email" class="form-control" name="website_email2" id="website_email2"
                                            value="{{ $data?->website_email2 ?? null }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Website Social Links</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_facebook">Website Facebook Link</label>

                                        <input type="text" class="form-control" name="website_facebook"
                                            id="website_facebook" value="{{ $data?->website_facebook ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_twitter">Website Twitter Link</label>
                                        <input type="text" class="form-control" name="website_twitter"
                                            id="website_twitter" value="{{ $data?->website_twitter ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_youtube">Website Youtube Link</label>
                                        <input type="text" class="form-control" name="website_youtube"
                                            id="website_youtube" value="{{ $data?->website_youtube ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_instagram">Website Instagram Link</label>
                                        <input type="text" class="form-control" name="website_instagram"
                                            id="website_instagram" value="{{ $data?->website_instagram ?? null }}">
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Invoice Prefix </h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="invoice_prefix">Invoice Prefix</label>
                                        <input type="text" class="form-control" id="invoice_prefix"
                                            name="invoice_prefix" value="{{ $data?->invoice_prefix }}">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 col-12">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Website CopyRight Text</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <textarea class="form-control form-control-textarea" name="website_copyright_text" id="website_copyright_text">{!! $data?->website_copyright_text ?? null !!}</textarea>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="fb_pixel">Facebook Pixel Code</label>
                                        <textarea class="form-control form-control-textarea" name="fb_pixel" id="fb_pixel" rows="2">{!! $data?->fb_pixel ?? null !!}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Website Logo</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Favicon</label>
                                        <div id="faviconPreview" class="mb-2">
                                            @if ($data?->website_favicon)
                                                <img width="64" height="64"
                                                    src="{{ $data?->get_favicon ? asset($data?->get_favicon->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                    alt="Website Logo">
                                            @endif
                                        </div>
                                        <input type="file" class="form-control mb-3" id="website_favicon"
                                            name="website_favicon">
                                        <input type="hidden" value="{{ $data?->website_favicon ?? null }}"
                                            name="website_favicon_old">


                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Header Logo</label>
                                        <div id="headerPreview" class="mb-2">
                                            @if ($data?->website_header_logo)
                                                <img width="150"
                                                    src="{{ $data?->get_header ? asset($data?->get_header->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                    alt="Website Logo">
                                            @endif
                                        </div>
                                        <input type="file" class="form-control mb-3" id="website_header_logo"
                                            name="website_header_logo">
                                        <input type="hidden" value="{{ $data?->website_header_logo ?? null }}"
                                            name="website_header_logo_old">


                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label">Footer Logo</label>
                                        <div id="footerPreview" class="mb-2">
                                            @if ($data?->website_footer_logo)
                                                <img width="150"
                                                    src="{{ $data?->get_footer ? asset($data?->get_footer->file_url) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}"
                                                    alt="Website Logo">
                                            @endif
                                        </div>
                                        <input type="file" class="form-control mb-3" id="website_footer_logo"
                                            name="website_footer_logo">
                                        <input type="hidden" value="{{ $data?->website_footer_logo ?? null }}"
                                            name="website_footer_logo_old">


                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Website Other Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="website_linkedin">Currency Sign</label>
                                        <input type="text" class="form-control" name="currency_sign"
                                            id="currency_sign" value="{{ $data?->currency_sign ?? null }}">
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="delivery_info">Bkash Merchant Number</label>
                                        <input type="text" class="form-control" id="bkash_merchant_numb"
                                            name="bkash_merchant_numb" value="{{ $data?->bkash_merchant_numb }}">
                                    </div>


                                    <div class="col-6 mb-3 d-flex">
                                        <input type="checkbox" class="form-check-input" name="guest_review"
                                            id="guest_review" value="1"
                                            {{ $data?->guest_review == 1 ? 'checked' : '' }}>
                                        <label class="form-label mx-2" for="guest_review">Guest Review</label>

                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Important Settings</h4>
                                </div>
                                <div class="card-body">
                                    <div class="col-12 mb-3">
                                        <label class="form-label" for="stock_alert">Stock Alert</label>
                                        <input type="text" class="form-control" id="stock_alert" name="stock_alert"
                                            value="{{ $data?->stock_alert }}">
                                    </div>
                                    <div class="col-6 mb-3 d-flex">
                                        <input type="checkbox" class="form-check-input" name="stock_management"
                                            id="stock_management" value="1"
                                            {{ $data?->stock_management == 1 ? 'checked' : '' }}>
                                        <label class="form-label mx-2" for="stock_management">Stock Advance
                                            Management</label>
                                    </div>
                                    @if (Auth::guard('admin')->user()->role_id == 1)
                                        <div class="col-6 mb-3 d-flex">
                                            <input type="checkbox" class="form-check-input" name="is_demo"
                                                id="is_demo" value="1"
                                                {{ $data?->is_demo == 1 ? 'checked' : '' }}>
                                            <label class="form-label mx-2" for="is_demo">
                                                Is Demo
                                            </label>
                                        </div>
                                    @endif

                                </div>
                            </div>


                            <div class="col-12">
                                <button type="submit" class="btn btn-success float-end mt-2">Update</button>
                            </div>
                        </div>


                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('js')
    {{-- <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script> --}}
    <script src="{{ asset('backEnd/assets/libs/summernote/summernote-lite.min.js') }}"></script>

    {{-- image preview --}}
    <script>
        $(document).ready(function() {
            $('#website_header_logo').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#headerPreview').html('<img src="' + e.target.result + '" width="50">');
                }
                reader.readAsDataURL(this.files[0]);
            });
            $('#website_footer_logo').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#footerPreview').html('<img src="' + e.target.result + '" width="50">');
                }
                reader.readAsDataURL(this.files[0]);
            });
            $('#website_favicon').change(function(e) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#faviconPreview').html('<img src="' + e.target.result + '" width="50">');
                }
                reader.readAsDataURL(this.files[0]);
            });


        });
    </script>
@endpush
