@extends('backEnd.admin.layouts.master')

@section('title')
    Home Page Settings
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
            padding: 10px;
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
                    <h3 class="mb-0">Home Page Setting</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="tab-left">
                        {{-- Sidebar Tabs --}}
                        <ul class="nav nav-tabs nav-tabs-left flex-column" id="conversionTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="top-header-tab" data-bs-toggle="tab" href="#top-header"
                                    role="tab">
                                    Top Header
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="hero-tab" data-bs-toggle="tab" href="#hero" role="tab">
                                    Hero
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="footer-tab" data-bs-toggle="tab" href="#footer" role="tab">
                                    Footer
                                </a>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content tab-content-right" id="conversionTabContent">
                            {{-- top-header --}}
                            <div class="tab-pane fade show active" id="top-header" role="tabpanel">
                                <form id="top-header-form" enctype="multipart/form-data">
                                    @csrf
                                    @php
                                        $top_header = App\HomePageSetting::where('section', 'top-header')->where('theme_id',activeTheme()->id)->first();
                                    @endphp
                                    <input type="hidden" name="name" value="top-header">
                                    <div class="card card-modern">
                                        <div class="card-header">
                                            <h5>Top Header Section </h5>
                                        </div>
                                        <div class="card-body">

                                            {{-- Common Pixel Script --}}
                                            <div class="mb-3">
                                                <label class="form-label">Free Shipping</label>
                                                <input type="text" name="free_shipping" class="form-control"
                                                    placeholder="Enter Free Shipping"
                                                    value="{{ isset($top_header->content['free_shipping']) ? $top_header->content['free_shipping'] : old('free_shipping') }}">
                                            </div>

                                        </div>
                                        <div class="card-footer text-end">
                                            <button type="submit" class="btn btn-success float-end">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            {{-- hero --}}
                            <div class="tab-pane fade" id="hero" role="tabpanel">
                                <form id="hero-form" enctype="multipart/form-data">
                                    @csrf
                                    @php
                                        $hero = App\HomePageSetting::where('section', 'hero')->where('theme_id',activeTheme()->id)->first();
                                    @endphp
                                    <input type="hidden" name="name" value="hero">
                                    <div class="card card-modern mb-3">
                                        <div class="card-header">
                                            <h5>Hero Section </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Call Support Text</label>
                                                <input type="text" name="call_support_text" class="form-control"
                                                    placeholder="Enter Call Support Text"
                                                    value="{{ isset($hero->content['call_support_text']) ? $hero->content['call_support_text'] : old('call_support_text') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Marquee Text </label>
                                                <textarea name="marquee_text" class="form-control" rows="5" placeholder="Enter Pixel Script">{{ isset($hero->content['marquee_text']) ? $hero->content['marquee_text'] : old('marquee_text') }}</textarea>
                                            </div>

                                        </div>
                                        <div class="card-footer text-end">
                                            <button type="submit" class="btn btn-success float-end">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- footer --}}
                            <div class="tab-pane fade" id="footer" role="tabpanel">
                                <form id="footer-form" enctype="multipart/form-data">
                                    @csrf
                                    @php
                                        $footer = App\HomePageSetting::where('section', 'footer')->where('theme_id',activeTheme()->id)->first();
                                    @endphp
                                    <input type="hidden" name="name" value="footer">
                                    <div class="card card-modern mb-3">
                                        <div class="card-header">
                                            <h5>Footer Section </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Payment Image</label>
                                                @if (isset($footer->content['payment_url']))
                                                    <img src="{{ asset($footer->content['payment_url']) }}" alt="Image"
                                                        style="width: 250px; height: 50px;object-fit: contain">
                                                @endif
                                                <input type="file" name="payment_url" class="form-control">
                                                <input type="hidden" name="old_payment_url" class="form-control" value="{{ isset($footer->content['payment_url']) ? $footer->content['payment_url'] : old('payment_url') }}">

                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Newsletter Text</label>
                                                <textarea name="newsletter_text" class="form-control" rows="5" placeholder="Enter Pixel Script">{{ isset($footer->content['newsletter_text']) ? $footer->content['newsletter_text'] : old('newsletter_text') }}</textarea>
                                            </div>

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

            var lastTab = localStorage.getItem('lastActiveTab');
            if (lastTab) {
                $('.nav-tabs-left .nav-link').removeClass('active');
                $('.tab-content .tab-pane').removeClass('show active');

                $('#' + lastTab + '-tab').addClass('active');
                $('#' + lastTab).addClass('show active');
            }

            // 🔹 Save active tab when clicked
            $('.nav-tabs-left .nav-link').on('shown.bs.tab', function(e) {
                var id = $(e.target).attr('href').replace('#', ''); // get tab id
                localStorage.setItem('lastActiveTab', id);
            });

            function submitForm(formId) {
                $(formId).on('submit', function(e) {
                    e.preventDefault();

                    let form = this;
                    let $submitBtn = $(form).find('button[type="submit"]');
                    $submitBtn.prop('disabled', true);

                    let formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('admin.home.page.setting.update') }}",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if (res.success === true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 1200
                                });

                                // 🔹 Keep the current tab active
                                var activeTab = $(form).closest('.tab-pane').attr('id');
                                localStorage.setItem('lastActiveTab', activeTab);
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        },
                        complete: function() {
                            $submitBtn.prop('disabled', false);
                        }
                    });
                });
            }

            submitForm('#top-header-form');
            submitForm('#hero-form');
            submitForm('#footer-form');

        });
    </script>
@endpush
