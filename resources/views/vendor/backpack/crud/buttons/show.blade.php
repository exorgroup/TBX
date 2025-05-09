@if ($crud->hasAccess('show', $entry))
    @if (!$crud->model->translationEnabled())
        {{-- Check if the record is valid or user is admin (or if isValid method doesn't exist) --}}
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            {{-- Single show button --}}
            <a href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}" bp-button="show" class="btn btn-sm btn-outline-success btn-pill">
                <i class="la la-eye"></i> <span>{{ trans('backpack::crud.preview') }}</span>
            </a>
        @else
            {{-- Disabled show button for invalid records (non-admin users) --}}
            <a href="javascript:void(0)" bp-button="show" class="btn btn-sm btn-outline-success btn-pill disabled" onclick="return false;" title="Only administrators can view invalid records" style="opacity: 0.6; cursor: not-allowed;">
                <i class="la la-eye"></i> <span>{{ trans('backpack::crud.preview') }}</span>
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