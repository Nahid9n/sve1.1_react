@foreach ($children as $child)

    @php
        $flags = $child->extra_fields ?? [];
    @endphp

    <div class="d-flex align-items-center mb-1">

        <svg style="margin-top: -5px;" xmlns="http://www.w3.org/2000/svg"
             class="icon icon-tabler icon-tabler-corner-down-right"
             width="15" height="15" viewBox="0 0 24 24" stroke-width="1.5"
             stroke="currentColor" fill="none" stroke-linecap="round"
             stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M6 6v6a3 3 0 0 0 3 3h10l-4 -4m0 8l4 -4"></path>
        </svg>

        <span class="ms-1">{{ $child->category_name }}</span>

        {{-- Add Sub --}}
        <button class="btn btn-success btn-sm btn-xs add_sub_cat_btn ms-2"
                data-id="{{ $child->id }}"
                data-name="{{ $child->category_name }}">
            <i class="ti ti-plus"></i>
        </button>

        {{-- Edit --}}
        <a href="javascript:void(0)" class="edit_sub_cat_btn ms-2"
           data-id="{{ $child->id }}"
           data-parent_id="{{ $child->parent_id }}"
           data-name="{{ $child->category_name }}"
           data-is_show_home="{{ $child->is_show_home }}"
           data-status="{{ $child->status }}"
           data-slug="{{ $child->slug }}"
           data-file_url="{{ $child->file_url }}"
           @foreach ($flags as $k => $v)
           @if (in_array($k, $targetKeys))
           data-{{ $k }}="{{ $v }}"
            @endif
            @endforeach
        >
            <i class="ti ti-edit"></i>
        </a>

        {{-- Delete --}}
        <a href="{{ route('admin.category.delete', $child->id) }}"
           class="ms-2 text-danger"
           onclick="return confirm('Are you sure to delete this?')">
            <i class="ti ti-trash"></i>
        </a>
    </div>

    {{-- Recursive children --}}
    @if ($child->childrenRecursive->count())
        <div class="ms-4 mt-1">
            @include('backEnd.admin.categories.category_row', ['children' => $child->childrenRecursive, 'targetKeys' => $targetKeys])
        </div>
    @endif

@endforeach
