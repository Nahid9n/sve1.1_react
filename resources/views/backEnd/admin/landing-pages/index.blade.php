@extends('backEnd.admin.layouts.master')

@section('title', 'Landing Pages')

@section('content')
    <div class="container-xl">
        <div class="mt-3 d-print-none">
            <h3>Landing Pages</h3>

            <a href="{{ route('admin.landing.pages.create') }}" class="btn btn-success btn-sm add-btn">
                <i class="ti ti-plus"></i> Add Pages
            </a>

            <div class="card mt-3">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Theme</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $key => $page)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($page->theme->imageFile)
                                            <img src="{{ asset($page->theme->imageFile->file_url) }}" width="80"
                                                class="rounded">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('landing-theme.home', $page->slug) }}">
                                            {{ $page->title }}
                                        </a>
                                    </td>
                                    <td>{{ $page->theme->title ?? '-' }}</td>
                                    <td>{{ $page->status ? 'Active' : 'Inactive' }}</td>
                                    <td class="w-1">
                                        <a href="{{ route('admin.landing.pages.customize', $page->slug) }}"
                                            class="btn-gradient-primary  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                            <i class="ti ti-brush"></i> Customize
                                        </a>
                                        <a href="{{ route('admin.landing.pages.edit', $page->id) }}"
                                            class="btn-gradient-info border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.landing.pages.delete', $page->id) }}"
                                            class="btn-gradient-danger border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1"
                                            onclick="return confirm('Delete this?')">
                                            <i class="ti ti-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
