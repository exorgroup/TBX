@if ($crud->get('reorder.enabled') && $crud->hasAccess('reorder'))
    <a href="{{ url($crud->route.'/reorder') }}" bp-button="reorder" data-toggle="tooltip" 
        title="{{ trans('backpack::crud.reorder') }}"
        class="btn btn-icon btn-outline-primary btn-pill" data-style="zoom-in">
        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrows-sort"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 9l4 -4l4 4m-4 -4v14" /><path d="M21 15l-4 4l-4 -4m4 4v-14" /></svg>
        <span>{{ trans('backpack::crud.reorder') }} {{ $crud->entity_name_plural }}</span>
    </a>
@endif