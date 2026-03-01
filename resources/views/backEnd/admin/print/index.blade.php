@extends('backEnd.admin.layouts.master')

@section('title')
    Print Settings
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/summernote/summernote-bs4.css') }}">
    <style>
        .layout-preview {
            cursor: zoom-in;
            transition: transform .2s ease;
        }

        .layout-preview:hover {
            transform: scale(1.02);
        }
        .layout-card {
            cursor: pointer;
            border: 2px solid transparent;
            transition: all .25s ease;
        }

        .layout-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,.12);
        }

        /* 🔥 selected */
        .layout-radio:checked + .layout-card {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
        }

        .image-wrapper{
            width: 100%;
            max-height: 100vh;
            overflow: visible; /* 🔥 important */
            position: relative;
            cursor: zoom-in;
            z-index: 1;
        }

        #previewImage{
            transition: transform .15s ease;
            transform-origin: center center;
            position: relative;
            z-index: 10;
        }


        /*#previewImage:hover {
            transform: scale(1.5);
        }*/

    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content position-relative">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h3 class="pageheader-title">Print Settings</h3>
                        </div>
                    </div>
                </div>
                <form action="{{ route('admin.settings.print.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="d-grid justify-content-end position-absolute" style="top: 30px; right: 25px">
                        <button class="btn btn-sm btn-success" style="width: 80px" type="submit"><i class="fa fa-save"></i> Save</button>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Single Print</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="layout-wrapper">

                                                <input type="radio"
                                                       name="single_print"
                                                       value="0"
                                                       {{$data->single_print == 0 ? 'checked' : ''}}
                                                       class="d-none layout-radio">

                                                <div class="card h-100 layout-card shadow">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <span class="fw-semibold">Default Layout</span>
                                                        @if($data->single_print == 0)
                                                            <span class="badge bg-primary">Active</span>
                                                        @endif
                                                    </div>

                                                    <div class="card-body p-2">
                                                        <img style="object-fit: contain; height: 310px" src="{{ asset('print-layout/default.png') }}"
                                                             class="img-fluid rounded layout-preview js-preview"
                                                             alt="Default Layout">
                                                    </div>

                                                    <div class="card-footer text-center bg-light">
                                                        <small class="text-muted">A4 • Portrait</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="layout-wrapper">

                                                <input type="radio"
                                                       name="single_print"
                                                       value="1"
                                                       {{$data->single_print == 1 ? 'checked' : ''}}
                                                       class="d-none layout-radio">

                                                <div class="card h-100 layout-card shadow">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <span class="fw-semibold">Layout 2</span>
                                                        @if($data->single_print == 1)
                                                            <span class="badge bg-primary">Active</span>
                                                        @endif
                                                    </div>

                                                    <div class="card-body p-2">
                                                        <img style="object-fit: contain; height: 310px" src="{{ asset('print-layout/single_layout_1.png') }}"
                                                             class="img-fluid rounded layout-preview js-preview"
                                                             alt="Default Layout">
                                                    </div>

                                                    <div class="card-footer text-center bg-light">
                                                        <small class="text-muted">A4 • Portrait</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="card mb-2">
                                <div class="card-header">
                                    <h4 class="mb-0">Bulk Print</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="layout-wrapper">

                                                <input type="radio"
                                                       name="bulk_print"
                                                       value="0"
                                                       {{$data->bulk_print == 0 ? 'checked' : ''}}
                                                       class="d-none layout-radio">

                                                <div class="card h-100 layout-card shadow">
                                                    <div class="card-header d-flex justify-content-between">
                                                        <span class="fw-semibold">Layout 1</span>
                                                        @if($data->bulk_print == 0)
                                                        <span class="badge bg-primary">Active</span>
                                                        @endif
                                                    </div>

                                                    <div class="card-body p-2">
                                                        <img style="object-fit: contain; height: 310px" src="{{ asset('print-layout/default_bulk.png') }}"
                                                             class="img-fluid rounded layout-preview js-preview"
                                                             alt="Default Layout">
                                                    </div>

                                                    <div class="card-footer text-center bg-light">
                                                        <small class="text-muted">A4 • Portrait</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="layout-wrapper">

                                                <input type="radio"
                                                       name="bulk_print"
                                                       value="1"
                                                       {{$data->bulk_print == 1 ? 'checked' : ''}}
                                                       class="d-none layout-radio">

                                                <div class="card h-100 layout-card shadow" >
                                                    <div class="card-header d-flex justify-content-between">
                                                        <span class="fw-semibold">Layout 2</span>
                                                        @if($data->bulk_print == 1)
                                                        <span class="badge bg-primary">Active</span>
                                                        @endif
                                                    </div>

                                                    <div class="card-body p-2">
                                                        <img style="object-fit: contain; height: 310px" src="{{ asset('print-layout/bulk_layout_1.png') }}"
                                                             class="img-fluid rounded layout-preview js-preview"
                                                             alt="Default Layout">
                                                    </div>

                                                    <div class="card-footer text-center bg-light">
                                                        <small class="text-muted">A4 • Portrait</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="layout-wrapper">
                                                <input type="radio"
                                                       name="bulk_print"
                                                       value="2"
                                                       {{$data->bulk_print == 2 ? 'checked' : ''}}
                                                       class="d-none layout-radio">

                                                <div class="card h-100 layout-card shadow" >
                                                    <div class="card-header d-flex justify-content-between">
                                                        <span class="fw-semibold">Label Printer 3/2</span>
                                                        @if($data->bulk_print == 2)
                                                        <span class="badge bg-primary">Active</span>
                                                        @endif
                                                    </div>

                                                    <div class="card-body text-center p-2">
                                                        <img style="object-fit: contain; height: 310px" src="{{ asset('print-layout/bulk_3_2_layout.png') }}"
                                                             class="img-fluid rounded layout-preview js-preview"
                                                             alt="Default Layout">
                                                    </div>

                                                    <div class="card-footer text-center bg-light">
                                                        <small class="text-muted">Label Printer</small>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="layoutPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Print Layout Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center bg-light">
                    <div class="image-wrapper">
                        <img id="previewImage"
                             src=""
                             class="img-fluid rounded shadow"
                             alt="Preview">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    {{-- <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script> --}}
    <script src="{{ asset('backEnd/assets/libs/summernote/summernote-lite.min.js') }}"></script>
    <script>
        $(document).on('click', '.layout-card', function (e) {

            // image click হলে এখানে আসবে না
            if ($(e.target).hasClass('js-preview')) return;

            // current card এর radio select করো
            let radio = $(this).siblings('.layout-radio');

            radio.prop('checked', true).trigger('change');
        });
    </script>

    <script>

        $(document).on('click', '.js-preview', function (e) {
            e.preventDefault();
            e.stopPropagation(); // card select trigger বন্ধ করবে

            // 🔥 exact clicked image
            let src = $(this).attr('src');

            $('#previewImage').attr('src', src);
            $('#layoutPreviewModal').modal('show');
        });
    </script>

    <script>
        const img = document.getElementById('previewImage');
        const wrapper = img.parentElement;
        let scale = 1.5;

        wrapper.addEventListener('mousemove', (e) => {
            const rect = wrapper.getBoundingClientRect();

            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;

            img.style.transformOrigin = `${x}% ${y}%`;
            img.style.transform = `scale(${scale})`;
        });

        wrapper.addEventListener('mouseleave', () => {
            img.style.transform = 'scale(1)';
            img.style.transformOrigin = 'center center';
        });
    </script>

@endpush

