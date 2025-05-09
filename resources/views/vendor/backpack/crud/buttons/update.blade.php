@if ($crud->hasAccess('update', $entry))
    @if (!$crud->model->translationEnabled())
        {{-- Check if the record is valid or user is admin --}}
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            {{-- Single edit button --}}
            <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" bp-button="update" class="btn btn-sm btn-outline-info btn-pill">
                <i class="la la-edit"></i> <span>{{ trans('backpack::crud.edit') }}</span>
            </a>
        @else
            {{-- Disabled edit button for invalid records (non-admin users) --}}
            <a href="javascript:void(0)" bp-button="update" class="btn btn-sm btn-outline-info btn-pill disabled" onclick="return false;" title="Only administrators can edit invalid records" style="opacity: 0.6; cursor: not-allowed;">
                <i class="la la-edit"></i> <span>{{ trans('backpack::crud.edit') }}</span>
            </a>
        @endif
    @else
        {{-- Check if the record is valid or user is admin --}}
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            {{-- Edit button group --}}
            <div class="btn-group">
              <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}" class="btn btn-sm btn-link pr-0">
                <span><i class="la la-edit"></i> {{ trans('backpack::crud.edit') }}</span>
              </a>
              <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                    <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?_locale={{ $key }}">{{ $locale }}</a>
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