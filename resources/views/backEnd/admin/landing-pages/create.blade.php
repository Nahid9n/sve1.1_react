@extends('backEnd.admin.layouts.master')

@section('title', 'Create Landing Page')

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">

    <style>
        /* Grid for themes */
        .theme-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* Card */
        .theme-card {
            width: 260px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid transparent;
            background: #fff;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            transition: 0.25s;
            cursor: pointer;
            position: relative;
        }

        .theme-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Selected Card */
        .theme-selected {
            border-color: #198754 !important;
            box-shadow: 0 0 15px rgba(25, 135, 84, 0.5);
        }

        /* Theme image */
        .theme-image {
            width: 100%;
            aspect-ratio: 4/3;
            background: #f3f3f3;
            position: relative;
        }

        .theme-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Footer */
        .theme-footer {
            padding: 10px;
            text-align: center;
            font-weight: 600;
            background: #f8f9fa;
        }

        /* Preview button */
        .preview-btn-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            z-index: 20;
        }

        .theme-card:hover .preview-btn-box {
            display: block;
        }

        /* Dark overlay on hover */
        .theme-card:hover .theme-image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 10;
        }

        /* Selected badge */
        .theme-selected-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            display: none;
            background: #198754;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            z-index: 25;
        }

        .theme-card.theme-selected .theme-selected-badge {
            display: inline-block;
        }

        /* Error highlight for theme section */
        .theme-error {
            border: 2px solid #dc3545 !important;
            padding: 8px;
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container-xl mt-4">

        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <h3 class="card-title">Create Landing Page</h3>

                <a href="{{ route('admin.landing.pages.index') }}" class="btn btn-tabler btn-sm back-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icon-tabler-chevrons-left">
                        <path d="M11 7l-5 5l5 5"></path>
                        <path d="M17 7l-5 5l5 5"></path>
                    </svg> Back
                </a>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.landing.pages.store') }}" method="POST">
                    @csrf

                    <div class="row my-3">
                        <div class="col-md-6">
                            <label class="form-label">Page Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Select Products (Multiple)</label>
                            <select name="product_ids[]" class="form-select select2" multiple>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}"
                                        {{ collect(old('product_ids'))->contains($p->id) ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h4 class="mt-4">Select Theme</h4>

                    <!-- CATEGORY TABS -->
                    <ul class="nav nav-tabs mt-3" role="tablist">
                        @foreach ($categories as $cat)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($loop->first) active @endif"
                                    data-bs-toggle="tab" data-bs-target="#tab-{{ $cat->id }}" type="button">
                                    {{ $cat->title }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- TAB CONTENT -->
                    <div class="tab-content border p-3 mt-2 @error('theme_id') theme-error @enderror">

                        @foreach ($categories as $cat)
                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                id="tab-{{ $cat->id }}">

                                <div class="theme-grid mt-3">

                                    @foreach ($cat->themes as $theme)
                                        <div class="theme-card" data-id="{{ $theme->id }}">

                                            <span class="theme-selected-badge">Selected</span>

                                            <div class="preview-btn-box">
                                                <a target="_blank" href="{{ route('admin.landing.theme.preview', $theme->slug) }}" class="btn btn-primary btn-sm">
                                                    <i class="ti ti-eye"></i> Preview
                                                </a>
                                            </div>

                                            <div class="theme-image">
                                                <img src="{{ asset($theme->imageFile->file_url) }}" alt="">
                                            </div>

                                            <div class="theme-footer">
                                                {{ $theme->title }}
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        @endforeach

                    </div>

                    @error('theme_id')
                        <div class="text-danger mt-2"><strong>{{ $message }}</strong></div>
                    @enderror

                    <input type="hidden" name="theme_id" id="theme_id" value="{{ old('theme_id') }}" required>

                    <button type="submit" class="btn btn-success mt-4">Create Page</button>

                </form>

            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(function() {

            $('.select2').select2();

            // THEME SELECT HANDLER
            $(".theme-card").on("click", function() {

                $(".theme-card").removeClass("theme-selected");
                $(this).addClass("theme-selected");

                $("#theme_id").val($(this).data("id"));

                toastr.success("Theme selected!");
            });

            // Scroll to theme section if validation error
            @if ($errors->has('theme_id'))
                $('html, body').animate({
                    scrollTop: $(".tab-content").offset().top - 200
                }, 500);
            @endif

        });
    </script>
@endpush
