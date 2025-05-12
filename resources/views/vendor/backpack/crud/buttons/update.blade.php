@if ($crud->hasAccess('update', $entry))
    @if (!$crud->model->translationEnabled())
        {{-- Check if the record is valid or user is admin --}}
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            {{-- Single edit button --}}
            {{-- Check for a custom CRUD setting to enable confirmation --}}
            @php
                // Check if the custom 'confirm_edit_operation' setting is true for this CRUD
                $confirmEdit = $crud->get('confirm_edit_operation') ?? false;
            @endphp
            <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}"
               data-toggle="tooltip"
               title="{{ trans('backpack::crud.edit') }}"
               bp-button="update"
               class="btn btn-icon btn-outline-info btn-pill"
               data-route="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" {{-- Add data-route for JS --}}
               @if($confirmEdit)
                   onclick="confirmEditEntry(this); return false;" {{-- Call JS function and prevent default --}}
               @endif
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 9 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
            </a>
        @else
            {{-- Disabled edit button for invalid records (non-admin users) --}}
            <a href="javascript:void(0)" bp-button="update" data-toggle="tooltip" title="{{ trans('backpack::crud.edit') }}" class="btn btn-icon btn-outline-info btn-pill disabled" onclick="return false;" title="Only administrators can edit invalid records" style="opacity: 0.6; cursor: not-allowed;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 9 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
            </a>
        @endif
    @else
        {{-- Check if the record is valid or user is admin --}}
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            {{-- Edit button group --}}
            {{-- Check for a custom CRUD setting to enable confirmation --}}
            @php
                 $confirmEdit = $crud->get('confirm_edit_operation') ?? false; // Check for a property on the entry
            @endphp
            <div class="btn-group">
                <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}"
                   class="btn btn-sm btn-link pr-0"
                   data-route="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" {{-- Add data-route for JS --}}
                   @if($confirmEdit)
                       onclick="confirmEditEntry(this); return false;" {{-- Call JS function and prevent default --}}
                   @endif
                >
                    <span><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</span>
                </a>
                <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
                    @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                        <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?_locale={{ $key }}">{{ $locale }}">
                           {{ $locale }}
                        </a>
                    @endforeach
                </ul>
            </div>
        @else
            {{-- Disabled edit button group for invalid records (non-admin users) --}}
            <div class="btn-group">
                <a href="javascript:void(0)" class="btn btn-sm btn-link pr-0 disabled" onclick="return false;" title="Only administrators can edit invalid records" style="opacity: 0.6; cursor: not-allowed;">
                    <span><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</span>
                </a>
                <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1 disabled" style="opacity: 0.6; cursor: not-allowed;">
                    <span class="caret"></span>
                </a>
            </div>
        @endif
    @endif
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
{{-- Use a unique basset block name for the edit button script --}}
@bassetBlock('backpack/crud/buttons/edit-button-confirm-'.app()->getLocale().'.js')
<script>
    // Ensure the confirmEditEntry function is defined only once
    if (typeof confirmEditEntry != 'function') {
        function confirmEditEntry(button) {
            // Get the edit route from the data-route attribute
            var route = $(button).attr('data-route');

            // Show the SweetAlert confirmation dialog
            swal({
                title: "{!! trans('backpack::base.warning') !!}", // Using Backpack's warning translation
                text: "{!! trans('backpack::crud.confirm_edit') ?? 'Are you sure you want to edit this item?' !!}", // Using our new translation key
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "{!! trans('backpack::crud.cancel') !!}", // Using Backpack's cancel translation
                        value: null,
                        visible: true,
                        className: "bg-secondary",
                        closeModal: true,
                    },
                    confirm: { // Changed from 'delete' to 'confirm' for clarity
                        text: "{!! trans('backpack::crud.edit') !!}", // Using Backpack's edit translation
                        value: true,
                        visible: true,
                        className: "bg-info", // Use info color for edit confirmation
                    },
                },
                dangerMode: false, // Not a dangerous operation like delete
            }).then((value) => {
                if (value) {
                    // If confirmed, navigate to the edit route
                    window.location.href = route;
                }
            });
        }
    }
</script>
@endBassetBlock
@if (!request()->ajax()) @endpush @endif 

ff

{{-- Note: We are using a new translation key 'backpack::crud.confirm_edit'.
     You should add this key and its translation to your Backpack language files.
     If the key is not found, it will fallback to the default 'Are you sure you want to edit this item?'.

     To activate the confirmation for a specific CRUD controller, add this line
     in its setup() method:

     $this->crud->set('confirm_edit_operation', true);
--}}
