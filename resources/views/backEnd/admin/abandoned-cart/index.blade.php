@extends('backEnd.admin.layouts.master')
@section('title')
    Abandoned Carts
@endsection
@php
    $setting = DB::table('web_settings')->first();
    $theme = App\Theme::where('status', 1)->first();
@endphp
@section('css')
    <style>
        .row>* {
            padding-right: calc(var(--tblr-gutter-x) * .4);
            padding-left: calc(var(--tblr-gutter-x) * .4);
        }
    </style>
@endsection
@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <h3> Abandoned Carts</h3>
                </div>
            </div>
            <div class="row row-deck row-cards">
                <div class="col-12">

                    <div class="card" style="border-top: none">
                        <div class="d-flex justify-content-between align-items-center m-2">

                            {{-- LEFT: Bulk Order --}}
                            <div>
                                <button id="bulkOrderBtn" class="btn btn-sm btn-success">
                                    Bulk Create Order
                                </button>
                            </div>

                            {{-- RIGHT: Search --}}
                            <form method="GET" action="{{ route('admin.abandoned.cart') }}">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Search by phone or customer name"
                                        autocomplete="off">
                                    <button class="btn btn-sm btn-primary" type="submit">Search</button>

                                    @if (request('search'))
                                        <a class="btn btn-sm btn-danger mx-1" href="{{ route('admin.abandoned.cart') }}">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>

                        </div>

                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>
                        <div class="table-responsive">
                            <table class="table align-top card-table datatable">
                                <thead>
                                    <tr>
                                        <th width="1px" class="w-1">
                                            <input class="form-check-input m-0 align-middle" type="checkbox"
                                                aria-label="Select all invoices" id="master">
                                        </th>
                                        <th width="1%">SL</th>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Customer Info</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Note</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 1)
                                    @if (count($data) > 0)
                                        @foreach ($data as $item)
                                            <tr id="tr_{{ $item->id }}"
                                                @if ($i % 2 == 0) style="background-color:#f5f5f5" @endif>
                                                <td>
                                                    <input class="form-check-input m-0 align-middle sub_chk"
                                                        type="checkbox">
                                                </td>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    {{ $item->id }}
                                                </td>
                                                <td class="text-wrap" align="justify" width="8%">
                                                    {{ date('d-m-Y h:i:s A', strtotime($item->created_at)) }}
                                                </td>

                                                <td class="text-wrap customer-cell" data-id="{{ $item->id }}"
                                                    align="justify" width="30%">

                                                    {{-- NAME --}}
                                                    <div class="editable" data-field="customer_name"
                                                        data-value="{{ $item->customer_name }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon-tabler icon-tabler-user" width="15"
                                                            height="15" viewBox="0 0 24 24" stroke-width="2.5"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M12 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                                        </svg>
                                                        <span class="editable-text" style="cursor:pointer;">

                                                            {{ $item->customer_name }}<i
                                                                class="ti ti-pencil ms-1 text-danger"></i>
                                                        </span>

                                                        <div class="editable-edit d-none">
                                                            <input type="text"
                                                                class="form-control form-control-sm editable-input">
                                                            <div class="mt-1 text-end">
                                                                <i class="ti ti-check text-success editable-save"
                                                                    style="cursor:pointer;font-size:18px"></i>
                                                                <i class="ti ti-x text-danger editable-cancel ms-2"
                                                                    style="cursor:pointer;font-size:18px"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- PHONE --}}
                                                    <div class="editable mt-1" data-field="customer_phone"
                                                        data-value="{{ $item->customer_phone }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon-tabler icon-tabler-phone" width="15"
                                                            height="15" viewBox="0 0 24 24" stroke-width="2.5"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path
                                                                d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                                            </path>
                                                        </svg>
                                                        <span class="editable-text" style="cursor:pointer;">

                                                            {{ $item->customer_phone }} <i
                                                                class="ti ti-pencil ms-1 text-danger"> <a
                                                                    href="tel:{{ $item->customer_phone }}"><span
                                                                        class="btn btn-success btn-sm">Call</span></a></i>
                                                        </span>

                                                        <div class="editable-edit d-none">
                                                            <input type="text"
                                                                class="form-control form-control-sm editable-input">
                                                            <div class="mt-1 text-end">
                                                                <i class="ti ti-check text-success editable-save"
                                                                    style="cursor:pointer;font-size:18px"></i>
                                                                <i class="ti ti-x text-danger editable-cancel ms-2"
                                                                    style="cursor:pointer;font-size:18px"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- ADDRESS --}}
                                                    <div class="editable mt-1" data-field="customer_address"
                                                        data-value="{{ $item->customer_address }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="icon-tabler icon-tabler-map-pin" width="15"
                                                            height="15" viewBox="0 0 24 24" stroke-width="2.5"
                                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                            <path d="M12 11m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                                            <path
                                                                d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z">
                                                            </path>
                                                        </svg>
                                                        <span class="editable-text" style="cursor:pointer;">

                                                            {{ $item->customer_address ?: 'Double click to add address' }}<i
                                                                class="ti ti-pencil ms-1 text-danger"></i></span>

                                                        <div class="editable-edit d-none">
                                                            <textarea class="form-control form-control-sm editable-input" rows="2"></textarea>
                                                            <div class="mt-1 text-end">
                                                                <i class="ti ti-check text-success editable-save"
                                                                    style="cursor:pointer;font-size:18px"></i>
                                                                <i class="ti ti-x text-danger editable-cancel ms-2"
                                                                    style="cursor:pointer;font-size:18px"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </td>
                                                <td class="text-wrap" align="justify" width="20%">
                                                    {{-- @dd(json_decode($item->abandoned_item, true)) --}}
                                                    <table>
                                                        @foreach (json_decode($item->abandoned_item, true) as $key => $abandoned_item)
                                                            {{-- @dd($abandoned_item) --}}
                                                            <?php
                                                            $product = \App\Product::with('get_thumb')->where('id', $abandoned_item['product_id'])->first();
                                                            ?>
                                                            {{-- @dd($product) --}}
                                                            <tr>
                                                                <td class="p-0">
                                                                    @if (isset($product->get_thumb))
                                                                        <img style="height: 40px;width: 40px;max-width: 40px;margin-right: 5px;margin-bottom: 10px;border-radius: 3px;cursor: pointer"
                                                                            class="show-img"
                                                                            data-img="{{ asset($product->get_thumb ? $product->get_thumb->file_url : '') }}"
                                                                            src="{{ asset($product->get_thumb ? $product->get_thumb->file_url : '') }}"
                                                                            alt="">
                                                                    @endif
                                                                    {{-- @dd($product->slug) --}}
                                                                </td>
                                                                <td class="align-top p-0 ps-2">
                                                                    <span class="text-dark"><a
                                                                            href="{{ route('single.product', $product->slug) }}">{{ $product->name }}</a>
                                                                        (x {{ $abandoned_item['qty'] }})
                                                                    </span>
                                                                    {{-- <a
                                                                        href="{{ route('single.product', $product->slug) }}">{{ $product->name }}</a> --}}
                                                                    {{-- @if ($abandoned_item['variant'])
                                                                        <br>
                                                                        <small class="fw-bold text-primary">
                                                                            ({{ $abandoned_item['variant'] }})
                                                                        </small>
                                                                    @endif --}}
                                                                    @if (!empty($abandoned_item['variant']))
                                                                        <br>
                                                                        <?php
                                                                        $ids = explode('-', $abandoned_item['variant']);
                                                                        $attributes = \App\AttributeItem::with('attribute')->whereIn('id', $ids)->get();
                                                                        
                                                                        // dd($attributes);
                                                                        
                                                                        ?>
                                                                        @foreach ($attributes as $v_item)
                                                                            <?php
                                                                            $attrItem = $v_item->name;
                                                                            $attribute = $v_item->attribute->name;
                                                                            ?>
                                                                            @if ($loop->first)
                                                                            @else
                                                                            @endif
                                                                            <small
                                                                                class="badge bg-primary text-white">{{ $attribute }}
                                                                                : {{ $attrItem }}
                                                                            </small>
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>

                                                </td>
                                                <td>
                                                    {{ $setting?->currency_sign }}{{ $item->total }}
                                                </td>
                                                <td class="text-wrap note-cell" width="20%"
                                                    data-id="{{ $item->id }}" data-note="{{ $item->note ?? '' }}">
                                                    {{--                                                    <span class="note-text {{ $item->note ?: 'btn btn-sm btn-outline-danger' }}">{{ $item->note ?: 'Double click to add note' }}</span> --}}
                                                    <span class="note-text" title="Double click to edit note"
                                                        style="cursor:pointer;">
                                                        {{ $item->note ?: 'Double click to add note' }}
                                                        <i class="ti ti-pencil ms-1 text-danger"></i>
                                                    </span>
                                                    <div class="note-edit d-none">
                                                        <textarea class="form-control form-control-sm note-input" rows="2"></textarea>

                                                        <div class="mt-1 text-end">
                                                            <i class="ti ti-check text-success note-save"
                                                                style="cursor:pointer;font-size:18px"></i>
                                                            <i class="ti ti-x text-danger note-cancel ms-2"
                                                                style="cursor:pointer;font-size:18px"></i>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    @can('abandoned.order.create')
                                                        <a href="{{ route('admin.abandoned.cart.create.order', $item->id) }}"
                                                            class="btn-gradient-success  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                            <i class="ti ti-shopping-cart"></i> Create Order
                                                        </a>
                                                    @endcan
                                                    @can('abandoned.order.delete')
                                                        <a href="{{ route('admin.abandoned.cart.delete', $item->id) }}"
                                                            onclick="return confirm('Are You Sure?')"
                                                            class="btn-gradient-danger  border-0 btn-sm w-100 mb-1 d-flex justify-content-center align-items-center rounded gap-1">
                                                            <i class="ti ti-trash"></i> Delete
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="11" class="text-danger text-center fw-bold">No Data Found !
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div>
                            {{ $data->links('backEnd.admin.includes.paginate') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(document).on('change', '#master', function() {
            $('.sub_chk').prop('checked', $(this).is(':checked'));
        });
    </script>
    <script>
        $(document).on('click', '#bulkOrderBtn', function() {
            let selectedIds = [];

            $('.sub_chk:checked').each(function() {
                selectedIds.push($(this).closest('tr').attr('id').replace('tr_', ''));
            });

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No cart selected',
                    text: 'Please select at least one abandoned cart.',
                    timer: 1000,
                    showConfirmButton: false
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "Orders will be created for selected carts!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, create orders',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#dc3545',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Creating orders, please wait',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "{{ route('admin.abandoned.cart.bulk.create.order') }}",
                        type: "POST",
                        data: {
                            ids: selectedIds,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: 'Something went wrong!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Please try again later.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let phoneMap = {};

            $('.datatable tbody tr').each(function() {
                let phone = $(this).find('.phone-number')
                    .clone()
                    .children()
                    .remove()
                    .end()
                    .text()
                    .trim();

                if (!phone) return;

                if (!phoneMap[phone]) {
                    phoneMap[phone] = [];
                }
                phoneMap[phone].push(this);
            });

            $.each(phoneMap, function(phone, rows) {
                if (rows.length > 1) {
                    $(rows).css('background-color', '#f3c2c2');
                }
            });
        });
    </script>
    <script>
        /* Double click → edit */
        $(document).on('dblclick', '.note-cell', function() {
            let cell = $(this);

            cell.find('.note-text').addClass('d-none');
            cell.find('.note-edit').removeClass('d-none');

            let note = cell.data('note');
            cell.find('.note-input').val(note).focus();
        });

        /* Cancel edit */
        $(document).on('click', '.note-cancel', function() {
            let cell = $(this).closest('.note-cell');

            cell.find('.note-edit').addClass('d-none');
            cell.find('.note-text').removeClass('d-none');
        });

        /* Save note */
        $(document).on('click', '.note-save', function() {
            let cell = $(this).closest('.note-cell');
            let id = cell.data('id');
            let note = cell.find('.note-input').val();

            $.ajax({
                url: "{{ route('admin.abandoned.cart.update.note') }}",
                type: "POST",
                data: {
                    id: id,
                    note: note,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {

                        cell.data('note', res.note);

                        cell.find('.note-text')
                            .text(res.note ? res.note : 'Double click to add note')
                            .css('background-color', '#d1e7dd'); // light green

                        cell.find('.note-edit').addClass('d-none');
                        cell.find('.note-text').removeClass('d-none');

                        // remove highlight after 1s
                        setTimeout(function() {
                            cell.find('.note-text').css('background-color', 'transparent');
                        }, 1000);
                    }
                }
            });
        });
    </script>
    <script>
        /* Double click → edit */
        $(document).on('dblclick', '.editable', function() {
            let box = $(this);

            box.find('.editable-text').addClass('d-none');
            box.find('.editable-edit').removeClass('d-none');

            let value = box.data('value');
            box.find('.editable-input').val(value).focus();
        });

        /* Cancel */
        $(document).on('click', '.editable-cancel', function() {
            let box = $(this).closest('.editable');

            box.find('.editable-edit').addClass('d-none');
            box.find('.editable-text').removeClass('d-none');
        });

        /* Save */
        $(document).on('click', '.editable-save', function() {
            let box = $(this).closest('.editable');
            let cell = box.closest('.customer-cell');

            let id = cell.data('id');
            let field = box.data('field');
            let value = box.find('.editable-input').val();

            $.ajax({
                url: "{{ route('admin.abandoned.cart.update.field') }}",
                type: "POST",
                data: {
                    id: id,
                    field: field,
                    value: value,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.success) {
                        box.data('value', value);

                        box.find('.editable-text')
                            .contents()
                            .filter(function() {
                                return this.nodeType === 3;
                            })
                            .remove();

                        box.find('.editable-text')
                            .prepend(' ' + (value || 'Click to add'))
                            .css('background-color', '#d1e7dd');

                        box.find('.editable-edit').addClass('d-none');
                        box.find('.editable-text').removeClass('d-none');

                        setTimeout(() => {
                            box.find('.editable-text').css('background-color', 'transparent');
                        }, 1000);
                    }
                }
            });
        });
    </script>
@endpush
