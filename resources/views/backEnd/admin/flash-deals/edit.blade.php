@extends('backEnd.admin.layouts.master')
@section('title', 'Edit Flash Deal')

@push('css')
    <link rel="stylesheet" href="{{ asset('backEnd/assets/libs/select2/css/select2.css') }}">
    <style>
        .hidden {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content">

                <div class="row mb-2 mt-3">
                    <div class="col-12">
                        <h3>
                            Edit Flash Deal
                            <small class="float-end">
                                <a href="{{ route('admin.flash.deals.index') }}" class="btn btn-dark btn-sm">
                                    <i class="ti ti-arrow-left"></i> Back
                                </a>
                            </small>
                        </h3>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.flash.deal.update', $deal->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Deal Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control"
                                        value="{{ old('title', $deal->title) }}" required>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Discount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="discount" class="form-control"
                                        value="{{ old('discount', $deal->discount) }}" required>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Discount Type</label>
                                    <select name="discount_type" class="form-control" id="discount_type">
                                        <option value="percentage"
                                            {{ $deal->discount_type == 'percentage' ? 'selected' : '' }}>
                                            Percentage</option>
                                        <option value="fixed" {{ $deal->discount_type == 'fixed' ? 'selected' : '' }}>Fixed
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Time <span class="text-danger">*</span> </label>
                                    <input type="datetime-local" name="start_time" class="form-control"
                                        value="{{ old('start_time', date('Y-m-d\TH:i', strtotime($deal->start_time))) }}"
                                        required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Time <span class="text-danger">*</span> </label>
                                    <input type="datetime-local" name="end_time" class="form-control"
                                        value="{{ old('end_time', date('Y-m-d\TH:i', strtotime($deal->end_time))) }}"
                                        required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Select Products <span class="text-danger">*</span> </label>
                                <select class="form-control select2" multiple name="product_ids[]">
                                    @foreach ($products as $p)
                                        <option value="{{ $p->id }}"
                                            @if (in_array($p->id, $deal->products->pluck('product_id')->toArray())) selected @endif>
                                            {{ $p->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ $deal->status == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $deal->status == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <button class="btn btn-success float-end mt-2">Update</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('backEnd/assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
