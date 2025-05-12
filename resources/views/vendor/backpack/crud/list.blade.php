@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.list') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="header-operation container-fluid animated fadeIn d-flex mb-2 align-items-baseline d-print-none" bp-section="page-header">
        <h1 class="text-capitalize mb-0" bp-section="page-heading">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</h1>
        <p class="ms-2 ml-2 mb-0" id="datatable_info_stack" bp-section="page-subheading">{!! $crud->getSubheading() ?? '' !!}</p>
    </section>
@endsection

@section('content')
  {{-- Default box --}}
  <div class="row" bp-section="crud-operation-list">

    {{-- THE ACTUAL CONTENT --}}
    <div class="{{ $crud->getListContentClass() }}">

        <div class="row mb-2 align-items-center">
          <div class="col-sm-9">
            @if ( $crud->buttons()->where('stack', 'top')->count() ||  $crud->exportButtons())
              <div class="d-print-none {{ $crud->hasAccess('create')?'with-border':'' }}">

                @include('crud::inc.button_stack', ['stack' => 'top'])

              </div>
            @endif
          </div>
          @if($crud->getOperationSetting('searchableTable'))
          <div class="col-sm-3">
            <div id="datatable_search_stack" class="mt-sm-0 mt-2 d-print-none">
              <div class="input-icon">
                <span class="input-icon-addon">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                </span>
                <input type="search" class="form-control" placeholder="{{ trans('backpack::crud.search') }}..."/>
              </div>
            </div>
          </div>
          @endif
        </div>

        {{-- Backpack List Filters --}}
        @if ($crud->filtersEnabled())
          @include('crud::inc.filters_navbar')
        @endif

        <div class="{{ backpack_theme_config('classes.tableWrapper') }}">
            <table
              id="crudTable"
              class="{{ backpack_theme_config('classes.table') ?? 'table table-striped table-hover nowrap rounded card-table table-vcenter card d-table shadow-xs border-xs' }}"
              data-responsive-table="{{ (int) $crud->getOperationSetting('responsiveTable') }}"
              data-has-details-row="{{ (int) $crud->getOperationSetting('detailsRow') }}"
              data-has-bulk-actions="{{ (int) $crud->getOperationSetting('bulkActions') }}"
              data-has-line-buttons-as-dropdown="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdown') }}"
              data-line-buttons-as-dropdown-minimum="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdownMinimum') }}"
              data-line-buttons-as-dropdown-show-before-dropdown="{{ (int) $crud->getOperationSetting('lineButtonsAsDropdownShowBefore') }}"
              cellspacing="0">
            <thead>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns() as $column)
                  @php
                  $exportOnlyColumn = $column['exportOnlyColumn'] ?? false;
                  $visibleInTable = $column['visibleInTable'] ?? ($exportOnlyColumn ? false : true);
                  $visibleInModal = $column['visibleInModal'] ?? ($exportOnlyColumn ? false : true);
                  $visibleInExport = $column['visibleInExport'] ?? true;
                  $forceExport = $column['forceExport'] ?? (isset($column['exportOnlyColumn']) ? true : false);
                  
                  // New column properties for customization
                  $columnWidth = $column['width'] ?? null;
                  $headerAlign = $column['headerAlign'] ?? null;
                  $cellAlign = $column['cellAlign'] ?? null;
                  
                  // Generate styles based on properties
                  $thStyles = [];
                  if ($columnWidth) {
                      $thStyles[] = "width: {$columnWidth}";
                  }
                  if ($headerAlign) {
                      $thStyles[] = "text-align: {$headerAlign}";
                  }
                  
                  $tdStyles = [];
                  if ($cellAlign) {
                      $tdStyles[] = "text-align: {$cellAlign}";
                  }
                  
                  $thStyleAttr = !empty($thStyles) ? ' style="'.implode('; ', $thStyles).'"' : '';
                  @endphp
                  <th
                    data-orderable="{{ var_export($column['orderable'], true) }}"
                    data-priority="{{ $column['priority'] }}"
                    data-column-name="{{ $column['name'] }}"
                    {{--
                    data-visible-in-table => if developer forced column to be in the table with 'visibleInTable => true'
                    data-visible => regular visibility of the column
                    data-can-be-visible-in-table => prevents the column to be visible into the table (export-only)
                    data-visible-in-modal => if column appears on responsive modal
                    data-visible-in-export => if this column is exportable
                    data-force-export => force export even if columns are hidden
                    --}}

                    data-visible="{{ $exportOnlyColumn ? 'false' : var_export($visibleInTable) }}"
                    data-visible-in-table="{{ var_export($visibleInTable) }}"
                    data-can-be-visible-in-table="{{ $exportOnlyColumn ? 'false' : 'true' }}"
                    data-visible-in-modal="{{ var_export($visibleInModal) }}"
                    data-visible-in-export="{{ $exportOnlyColumn ? 'true' : ($visibleInExport ? 'true' : 'false') }}"
                    data-force-export="{{ var_export($forceExport) }}"
                    @if(!empty($tdStyles)) data-cell-style="{{ implode('; ', $tdStyles) }}" @endif
                    {!! $thStyleAttr !!}
                  >
                    {{-- Bulk checkbox --}}
                    @if($loop->first && $crud->getOperationSetting('bulkActions'))
                      	{!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ( $crud->buttons()->where('stack', 'line')->count() )
                  <th data-orderable="false"
                      data-priority="{{ $crud->getActionsColumnPriority() }}"
                      data-visible-in-export="false"
                      data-action-column="true"
                      data-column-width="{{ $crud->get('actionsColumnWidth') ?? '120px' }}"
                      data-header-align="{{ $crud->get('actionsHeaderAlign') ?? 'center' }}"
                      data-cell-align="{{ $crud->get('actionsCellAlign') ?? 'center' }}"
                      style="width: {{ $crud->get('actionsColumnWidth') ?? '120px' }}; text-align: {{ $crud->get('actionsHeaderAlign') ?? 'center' }};"
                      >{{ trans('backpack::crud.actions') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                {{-- Table columns --}}
                @foreach ($crud->columns() as $column)
                  @php
                  // New column properties for customization (for footer)
                  $columnWidth = $column['width'] ?? null;
                  $headerAlign = $column['headerAlign'] ?? null;
                  
                  // Generate styles based on properties
                  $thStyles = [];
                  if ($columnWidth) {
                      $thStyles[] = "width: {$columnWidth}";
                  }
                  if ($headerAlign) {
                      $thStyles[] = "text-align: {$headerAlign}";
                  }
                  
                  $thStyleAttr = !empty($thStyles) ? ' style="'.implode('; ', $thStyles).'"' : '';
                  @endphp
                  <th {!! $thStyleAttr !!}>
                    {{-- Bulk checkbox --}}
                    @if($loop->first && $crud->getOperationSetting('bulkActions'))
                      	{!! View::make('crud::columns.inc.bulk_actions_checkbox')->render() !!}
                    @endif
                    {!! $column['label'] !!}
                  </th>
                @endforeach

                @if ( $crud->buttons()->where('stack', 'line')->count() )
                  <th style="width: {{ $crud->get('actionsColumnWidth') ?? '120px' }}; text-align: {{ $crud->get('actionsHeaderAlign') ?? 'center' }};">
                    {{ trans('backpack::crud.actions') }}
                  </th>
                @endif
              </tr>
            </tfoot>
          </table>
        </div>

        @if ( $crud->buttons()->where('stack', 'bottom')->count() )
            <div id="bottom_buttons" class="d-print-none text-sm-left">
                @include('crud::inc.button_stack', ['stack' => 'bottom'])
                <div id="datatable_button_stack" class="float-right float-end text-right hidden-xs"></div>
            </div>
        @endif

    </div>

  </div>

@endsection

@section('after_styles')
  {{-- DATA TABLES --}}
  @basset('https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css')
  @basset('https://cdn.datatables.net/fixedheader/3.3.1/css/fixedHeader.dataTables.min.css')
  @basset('https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css')

  {{-- CRUD LIST CONTENT - crud_list_styles stack --}}
  @stack('crud_list_styles')

   <style>
        .row-invalid {
          background-color: #f8d7da !important; /* Light red background */
          border: 1px solid #e24b57 !important; /* 1px solid red border on all sides */
          /*border-left-width: 5px !important;  Override right border width to 5px */
        }

        tr.row-invalid td:nth-child(even) { /* Specifically target even cells in invalid rows for box-shadow removal */
            box-shadow: none !important;
        }

        tr.row-invalid td {
            --tblr-table-accent-bg: #f0d0d3 !important; /* Override the accent background */
        }

    </style>
@endsection

@section('after_scripts')
  @include('crud::inc.datatables_logic')

  {{-- Add custom script to apply cell alignment styles --}}
 <script>
        $(document).ready(function() {

          $('[data-toggle="tooltip"]').tooltip(); 
            var crudTable = $('#crudTable').DataTable();

            // Apply cell alignment styles
            crudTable.on('draw.dt', function() {
                $('#crudTable thead th').each(function(index) {
                    var cellStyle = $(this).data('cell-style');
                    if (cellStyle) {
                        $('#crudTable tbody tr').each(function() {
                            $(this).find('td:eq(' + index + ')').attr('style', cellStyle);
                        });
                    }
                });

                // Apply row class by searching for the 'invalid-row-check' value in any column
                crudTable.rows().every(function() {
                    var rowData = this.data();
                    var isInvalidRow = false;
                    var isValidRow = false;

                    for (var i = 0; i < rowData.length; i++) {
                        var cellValue = $(rowData[i]).text().trim();
                        if (cellValue === 'invalid-row-check') {
                            isInvalidRow = true;
                            break; // No need to check further once found
                        } else if (cellValue === 'valid-row-check') {
                            isValidRow = true;
                        }
                    }
                    if (isInvalidRow) {
                        $(this.node()).addClass('row-invalid');
                    } else if (isValidRow) {
                        $(this.node()).removeClass('row-invalid');
                    }
                });
            });
        });
    </script>

  {{-- CRUD LIST CONTENT - crud_list_scripts stack --}}
  @stack('crud_list_scripts')

  
@endsection