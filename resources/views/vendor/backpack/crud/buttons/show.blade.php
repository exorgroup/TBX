@if ($crud->hasAccess('show', $entry))
    @if (!$crud->model->translationEnabled())
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            {{-- Single show button --}}
            <a href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}" bp-button="show" class="btn btn-icon btn-outline-success btn-pill" data-toggle="tooltip" title="{{ trans('backpack::crud.preview') }}">
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
            </a>
        @else
            {{-- Disabled show button for invalid records (non-admin users) --}}
            <a href="javascript:void(0)" bp-button="show" class="btn btn-icon btn-outline-success btn-pill disabled" onclick="return false;"  data-toggle="tooltip" title="{{ trans('backpack::crud.preview') }}" title="Only administrators can view invalid records" style="opacity: 0.6; cursor: not-allowed;">
              <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
            </a>
        @endif
    @else
        {{-- Check if the record is valid or user is admin (or if isValid method doesn't exist) --}}
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            {{-- Show button group --}}
            <div class="btn-group">
              <a href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}" class="btn btn-sm btn-link pr-0">
                <span><i class="la la-eye"></i> {{ trans('backpack::crud.preview') }}</span>
              </a>
              <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header">{{ trans('backpack::crud.preview') }}:</li>
                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                    <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?_locale={{ $key }}">{{ $locale }}</a>
                @endforeach
              </ul>
            </div>
        @else
            {{-- Disabled show button group for invalid records (non-admin users) --}}
            <div class="btn-group">
              <a href="javascript:void(0)" class="btn btn-sm btn-link pr-0 disabled" onclick="return false;" title="Only administrators can view invalid records" style="opacity: 0.6; cursor: not-allowed;">
                <span><i class="la la-eye"></i> {{ trans('backpack::crud.preview') }}</span>
              </a>
            </div>
        @endif
    @endif
@endif