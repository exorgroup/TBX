@if ($crud->hasAccess('revise'))
    {{-- Determine if the button should be disabled --}}
    @php
        $isDisabled = ! (isset($entry) && method_exists($entry, 'revisionHistory') && count($entry->revisionHistory) > 0);
        $tooltip = $isDisabled ? trans('revise-operation::revise.no_revisions') : trans('revise-operation::revise.revisions');
        $buttonClasses = $isDisabled ? 'btn btn-icon btn-outline-secondary btn-pill disabled' : 'btn btn-icon btn-outline-warning btn-pill';
    @endphp

    {{-- The button link --}}
    <a href="{{ $isDisabled ? '#' : url($crud->route.'/'.$entry->getKey().'/revise') }}"
       data-toggle="tooltip"
       title="{{ $tooltip }}"
       class="{{ $buttonClasses }}"
       @if ($isDisabled)
           aria-disabled="true"
           onclick="return false;" {{-- Prevent click action when disabled --}}
       @endif
    >
        {{-- SVG icon for history --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-history">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 8l0 4l2 2" />
            <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" />
        </svg>
    </a>
@endif
