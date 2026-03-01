@extends('backEnd.admin.layouts.master')

@section('title')
    Promo Banner
@endsection

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <h2>Promotional Banner</h2>
                {{-- @dd($sections) --}}
                @if (count($sections) > 0)
                    <form action="{{ route('admin.promotional.banner.updateOrCreate') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            @foreach ($sections as $section)
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">
                                                {{ ucfirst($section->section) }} Section
                                            </h5>
                                        </div>

                                        <div class="card-body">
                                            @for ($i = 0; $i < $section->max_items; $i++)
                                                @php
                                                    $existingBanner = $section->banners->where('order', $i)->first();
                                                @endphp

                                                <div class="row mb-3">

                                                    {{-- Image Upload --}}
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                Banner Image - {{ $i + 1 }}
                                                            </label>

                                                            <input type="file"
                                                                name="banner_image[{{ $section->section }}][{{ $i }}]"
                                                                class="form-control image-input"
                                                                data-preview="preview-{{ $section->id }}-{{ $i }}">

                                                            {{-- Preview Image --}}
                                                            <img id="preview-{{ $section->id }}-{{ $i }}"
                                                                src="{{ $existingBanner && $existingBanner->image ? asset($existingBanner->image) : '' }}"
                                                                class="img-fluid mt-2"
                                                                style="max-height:120px; {{ $existingBanner && $existingBanner->image ? '' : 'display:none;' }}">
                                                        </div>
                                                    </div>

                                                    {{-- Banner Link --}}
                                                    <div class="col-md-12 mt-2">
                                                        <div class="form-group">
                                                            <label class="form-label">
                                                                Banner Link - {{ $i + 1 }}
                                                            </label>

                                                            <input type="text"
                                                                name="banner_link[{{ $section->section }}][{{ $i }}]"
                                                                value="{{ $existingBanner->link ?? '' }}"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                </div>

                                                @if ($i < $section->max_items - 1)
                                                    <hr>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="btn btn-success mt-3 ">
                            Save
                        </button>
                    </form>
                @else
                    <div class="alert alert-danger">
                        No Section Found
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.image-input').forEach(function(input) {

                input.addEventListener('change', function(e) {

                    let previewId = this.getAttribute('data-preview');
                    let previewImage = document.getElementById(previewId);

                    if (e.target.files.length > 0) {

                        let reader = new FileReader();

                        reader.onload = function(event) {
                            previewImage.src = event.target.result;
                            previewImage.style.display = "block";
                        };

                        reader.readAsDataURL(e.target.files[0]);
                    }

                });

            });

        });
    </script>
@endsection
