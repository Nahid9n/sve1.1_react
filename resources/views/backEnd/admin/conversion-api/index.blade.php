@extends('backEnd.admin.layouts.master')

@section('title')
    Conversion API
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/summernote/summernote-bs4.css') }}">
    <style>
        /* Modern Sidebar Tabs */
        .tab-left {
            display: flex;
            gap: 20px;
            min-height: 200px;
            /* minimum height */
            align-items: flex-start;
            /* left sidebar start from top */
        }

        .nav-tabs-left {
            flex-direction: column;
            width: 220px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 10px 0;
            /* make height dynamic */
            height: auto;
        }

        .tab-content-right {
            flex: 1;
        }


        .nav-tabs-left .nav-link {
            border: none;
            color: #495057;
            padding: 12px 20px;
            border-radius: 5px;
            margin: 5px 10px;
            transition: all 0.2s;
        }

        .nav-tabs-left .nav-link.active {
            background: #5B9EF2;
            color: #fff;
            font-weight: 600;
        }



        .card-modern {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            transition: 0.3s;
        }

        .card-modern:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header h5 {
            font-weight: 600;
            color: #333;
        }
    </style>
@endpush

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row mb-3">
                <div class="col-12">
                    <h3 class="mb-0">Conversion API</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="tab-left">
                        {{-- Sidebar Tabs --}}
                        <ul class="nav nav-tabs nav-tabs-left flex-column" id="conversionTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="facebook-tab" data-bs-toggle="tab" href="#facebook"
                                    role="tab">
                                    Facebook
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="youtube-tab" data-bs-toggle="tab" href="#youtube" role="tab">
                                    YouTube
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tiktok-tab" data-bs-toggle="tab" href="#tiktok" role="tab">
                                    TikTok
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="tiktok-tab" data-bs-toggle="tab" href="#tiktok" role="tab">
                                    TikTok
                                </a>
                            </li> --}}
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content tab-content-right" id="conversionTabContent">
                            {{-- Facebook --}}
                            <div class="tab-pane fade show active" id="facebook" role="tabpanel">
                                <form id="facebook-form">
                                    @csrf
                                    <?php $facebook = \App\ConversionApi::where('name', 'facebook')->first();
                                    ?>
                                    <input type="hidden" name="name" value="facebook">
                                    <div class="card card-modern">
                                        <div class="card-header">
                                            <h5>Facebook Pixel Setup</h5>
                                        </div>
                                        <div class="card-body">

                                            {{-- Common Pixel Script --}}
                                            <div class="mb-3">
                                                <label class="form-label">Pixel Script</label>
                                                <textarea name="fb_pixel_script" class="form-control" rows="5" placeholder="Enter Pixel Script">{{ isset($facebook->data['fb_pixel_script']) ? $facebook->data['fb_pixel_script'] : old('fb_pixel_script') }}</textarea>
                                            </div>

                                            {{-- Checkboxes --}}
                                            <div class="form-check mb-3">
                                                <input class="form-check-input fb-option" type="checkbox" name="fb_option"
                                                    value="builtin" id="fb-builtin"
                                                    {{ isset($facebook->data['fb_option']) && $facebook->data['fb_option'] == 'builtin' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="fb-builtin">CAPI Built In</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input fb-option" type="checkbox" name="fb_option"
                                                    value="gtm" id="fb-gtm"
                                                    {{ isset($facebook->data['fb_option']) && $facebook->data['fb_option'] == 'gtm' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="fb-gtm">CAPI with GTM</label>
                                            </div>
                                            @php
                                                $fbOption = $facebook->data['fb_option'] ?? null;
                                            @endphp

                                            {{-- Built In Fields --}}
                                            <div id="fb-builtin-input" class="mb-3"
                                                style="{{ $fbOption === 'builtin' ? '' : 'display: none;' }}">
                                                <label class="form-label">Pixel ID</label>
                                                <input type="text" name="fb_pixel_id" class="form-control"
                                                    value="{{ isset($facebook->data['fb_pixel_id']) ? $facebook->data['fb_pixel_id'] : old('fb_pixel_id') }}"
                                                    placeholder="Enter Pixel ID">

                                                <label class="mt-2 form-label">Access Token</label>
                                                <textarea name="fb_access_token" class="form-control" rows="5" placeholder="Enter Access Token">{{ isset($facebook->data['fb_access_token']) ? $facebook->data['fb_access_token'] : old('fb_access_token') }}</textarea>
                                            </div>

                                            {{-- GTM Fields --}}
                                            <div id="fb-gtm-input" class="mb-3"
                                                style="{{ $fbOption === 'gtm' ? '' : 'display: none;' }}">
                                                <label class="form-label">GTM Head Script</label>
                                                <textarea name="fb_gtm_head" class="form-control" rows="5" placeholder="Enter GTM Head Script">{{ isset($facebook->data['fb_gtm_head']) ? $facebook->data['fb_gtm_head'] : old('fb_gtm_head') }}</textarea>

                                                <label class="mt-2 form-label">GTM Body Script</label>
                                                <textarea name="fb_gtm_body" class="form-control" rows="5" placeholder="Enter GTM Body Script">{{ isset($facebook->data['fb_gtm_body']) ? $facebook->data['fb_gtm_body'] : old('fb_gtm_body') }}</textarea>
                                            </div>

                                        </div>
                                        <div class="card-footer text-end">
                                            <button type="submit" class="btn btn-success float-end">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            {{-- YouTube --}}
                            <div class="tab-pane fade" id="youtube" role="tabpanel">
                                <form id="youtube-form">
                                    @csrf
                                    <input type="hidden" name="name" value="youtube">
                                    <div class="card card-modern mb-3">
                                        <div class="card-header">
                                            <h5>YouTube Scripts</h5>
                                        </div>
                                        <div class="card-body">
                                            <textarea name="youtube_script" class="form-control summernote" rows="8">{{ isset($conversion->data['youtube_script']) ? $conversion->data['youtube_script'] : old('youtube_script') }}</textarea>
                                        </div>
                                        <div class="card-footer text-end">
                                            <button type="submit" class="btn btn-success float-end">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- TikTok --}}
                            <div class="tab-pane fade" id="tiktok" role="tabpanel">
                                <form id="tiktok-form">
                                    @csrf
                                    <input type="hidden" name="name" value="tiktok">
                                    <div class="card card-modern mb-3">
                                        <div class="card-header">
                                            <h5>TikTok Scripts</h5>
                                        </div>
                                        <div class="card-body">
                                            <textarea name="tiktok_script" class="form-control summernote" rows="8">{{ $conversion ? $conversion->tiktok_script : '' }}</textarea>
                                        </div>
                                        <div class="card-footer text-end">
                                            <button type="submit" class="btn btn-success float-end">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/libs/summernote/summernote-bs4.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                minHeight: 200
            });
            // Make checkboxes mutually exclusive
            $('.fb-option').change(function() {
                let selected = $(this).val();

                // Uncheck other
                $('.fb-option').not(this).prop('checked', false);

                // Show/hide corresponding fields
                if (selected === 'builtin' && $(this).is(':checked')) {
                    $('#fb-builtin-input').slideDown();
                    $('#fb-gtm-input').slideUp();
                } else if (selected === 'gtm' && $(this).is(':checked')) {
                    $('#fb-gtm-input').slideDown();
                    $('#fb-builtin-input').slideUp();
                } else {
                    $('#fb-builtin-input, #fb-gtm-input').slideUp();
                }
            });

            function submitForm(formId) {
                $(formId).on('submit', function(e) {
                    e.preventDefault();
                    let $form = $(this);
                    let $submitBtn = $form.find('button[type="submit"]');
                    $submitBtn.prop('disabled', true);
                    let data = {};
                    $form.serializeArray().forEach(function(item) {
                        data[item.name] = item.value;
                    });
                    $.post("{{ route('admin.marketing.api.update') }}", data, function(res) {
                        // console.log(res);
                        if (res.success === true) {
                            Swal.fire({
                                icon: 'success',
                                title: res.message,
                                showConfirmButton: false,
                                timer: 1200
                            })
                        }
                    }).fail(function(err) {
                        // console.log(err);
                    }).always(function() {
                        $submitBtn.prop('disabled', false);
                    });
                });
            }

            // Initialize forms
            submitForm('#facebook-form');
            submitForm('#youtube-form');
            submitForm('#tiktok-form');
        });
    </script>
@endpush
