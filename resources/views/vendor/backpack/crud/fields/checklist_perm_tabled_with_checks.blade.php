{{-- checklist --}}
@php
  $key_attribute = (new $field['model'])->getKeyName();
  $field['attribute'] = $field['attribute'] ?? (new $field['model'])->identifiableAttribute();
  $field['number_of_columns'] = $field['number_of_columns'] ?? 3;

  // calculate the checklist options
  if (!isset($field['options'])) {
      $field['options'] = $field['model']::all()->pluck($field['attribute'], $key_attribute)->toArray();
  } else {
      $field['options'] = call_user_func($field['options'], $field['model']::query());
  }

  asort($field['options']);

  // calculate the value of the hidden input
  $field['value'] = old_empty_or_null($field['name'], []) ??  $field['value'] ?? $field['default'] ?? [];
  if(!empty($field['value'])) {
      if (is_a($field['value'], \Illuminate\Support\Collection::class)) {
          $field['value'] = ($field['value'])->pluck($key_attribute)->toArray();
      } elseif (is_string($field['value'])){
        $field['value'] = json_decode($field['value']);
      }
  }

  // define the init-function on the wrapper
  $field['wrapper']['data-init-function'] =  $field['wrapper']['data-init-function'] ?? 'bpFieldInitChecklist';
@endphp

@include('crud::fields.inc.wrapper_start')
    
    <input type="hidden" value='@json($field['value'])' name="{{ $field['name'] }}">
<!-- hidden here -->
    
@include('crud::fields.inc.translatable_icon')
    <div class="card">
      <div class="card-header">
        <div>
          <h3 class="card-title">{!! $field['label'] !!}</h3> <br>
        
          <ul class="list-group list-group-horizontal">
            <li class="list-group-item" style="padding: 2px 10px; color:grey;  border:0px; ">
              navigation
            </li>
            <li class="list-group-item" style="padding: 2px 10px; border-top:0px;  border-bottom:0px;  border-left:0px; ">
              <a href="#a">a</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#b">b</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#c">c</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#d">d</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#e">e</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#f">f</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#g">g</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#h">h</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#i">i</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#j">j</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#k">k</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#l">l</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#m">m</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#n">n</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#o">o</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#p">p</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#q">q</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#r">r</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#s">s</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#t">t</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#u">u</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#v">v</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#w">w</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#x">x</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; ">
              <a href="#y">y</a>
            </li>
            <li class="list-group-item" style="padding:2px 10px; border-top:0px;  border-bottom:0px; border-right:0px">
              <a href="#z">z</a>
            </li>
          </ul>
        </div>
      </div>

      <div class="list-group list-group-flush overflow-auto" style="max-height: 35rem">
        @php($currTitle = "")
        @php($isFirstRow = true)
        @foreach ($field['options'] as $key => $option)
          @php($title = explode('_', $option ))

          @if ($title[0] != $currTitle)           
            @if (!$isFirstRow)              
                    </div>
                  </div>
                </div>
              </div>           
            @endif

            @if(substr($title[0],0,1) != substr($currTitle,0,1))
              <div id="{{strtolower(substr($title[0],0,1))}}" class="list-group-header sticky-top">{{ substr($title[0],0,1) }}</div>
            @endif

            @php($currTitle = $title[0])
            @php($isFirstRow = false)

            <div class="list-group-item"> 
              <div class="row">
                <div class="d-flex align-items-center"> 
                  <div class="w-25" id="0{{strtolower(substr($currTitle,0,1))}}">{{ $currTitle }}</div>
                  <div class="w-75 form-selectgroup">
          @endif    

          <label class="form-switch permission-label" style="padding-left: 0px; padding-right: 5px;">
            <input class="form-check-input permission-checkbox m-0 me-2" value="{{ $key }}" type="checkbox">
            
              @switch(substr($option , strlen($currTitle)+1) )
                @case('View')   
                @case('Read')
                  <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="read">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-perm icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    <span class="lock-icon"></span>
                  </span>
                @break
                
                @case('New')
                @case('Add')
                @case('Create')
                  <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="create">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-perm icon-tabler icon-tabler-file-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11l0 6" /><path d="M9 14l6 0" /></svg>
                    <span class="lock-icon"></span>
                  </span>
                @break
            
                @case('Update')    
                @case('Edit')
                  <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="update">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-perm icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                    <span class="lock-icon"></span>
                  </span>
                @break

                @case('Delete')
                  <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-perm icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                    <span class="lock-icon"></span>
                  </span>
                @break
            
                @case('Print')
                  <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="print">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-perm icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
                    <span class="lock-icon"></span>
                  </span>
                @break
                
                @case('History')
                  <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="history">
                    <svg  xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-history"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="1.5"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-history"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2" /><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5" /></svg>
                    <span class="lock-icon"></span>
                  </span>
                @break

                
                
               
            
                @default
                  <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="{{ substr($option, strlen($currTitle)+1) }}">
                    <span class="custom-permission-text">{{ substr($option, strlen($currTitle)+1) }}</span>
                    <span class="lock-icon"></span>
                  </span>
                @break
            @endswitch
          </label>
        @endforeach
      </div>
    </div>
  </div>
</div>
</div>
</div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

    <style>
      .permission-label {
        display: inline-block;
        margin-right: 8px;
        margin-bottom: 8px;
        position: relative;
        cursor: pointer;
      }
      
      .permission-checkbox {
        position: absolute;
        opacity: 0;
      }
      
      .permission-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        border-radius: 4px;
        transition: all 0.2s ease;
        background-color: transparent;
        position: relative;
      }
      
      .permission-checkbox:checked ~ .permission-btn {
        border: 2px solid #005500;
      }
      
      .permission-checkbox:not(:checked) ~ .permission-btn {
        border: 2px solid #550000;
      }
      
      .icon-perm {
        transition: all 0.2s ease;
      }
      
      .permission-checkbox:checked ~ .permission-btn .icon-perm {
        stroke: #005500;
      }
      
      .permission-checkbox:not(:checked) ~ .permission-btn .icon-perm {
        stroke: #550000;
      }
      
      .permission-checkbox:checked ~ .permission-btn .custom-permission-text {
        color: #005500;
        font-weight: bold;
      }
      
      .permission-checkbox:not(:checked) ~ .permission-btn .custom-permission-text {
        color: #550000;
        font-weight: bold;
      }
      
      .lock-icon {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 1px solid #dee2e6;
      }
      
      .permission-checkbox:checked ~ .permission-btn .lock-icon:after {
        content: "✓";
        color: #005500;
        font-weight: bold;
      }
      
      .permission-checkbox:not(:checked) ~ .permission-btn .lock-icon:after {
        content: "×";
        color: #550000;
        font-weight: bold;
      }
      
      .list-group-header {
        font-weight: bold;
        background-color: #e9ecef;
        padding: 0.5rem 1rem;
        text-transform: uppercase;
      }
    </style>
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        @bassetBlock('backpack/crud/fields/checklist-field.js')
        <script>
            function bpFieldInitChecklist(element) {
                var hidden_input = element.find('input[type=hidden]');
                var selected_options = JSON.parse(hidden_input.val() || '[]');
                var checkboxes = element.find('input[type=checkbox]');
                var container = element.find('.row');

                // set the default checked/unchecked states on checklist options
                checkboxes.each(function(key, option) {
                  var id = $(this).val();

                  if (selected_options.map(String).includes(id)) {
                    $(this).prop('checked', 'checked');
                  } else {
                    $(this).prop('checked', false);
                  }
                });

                // when a checkbox is clicked
                // set the correct value on the hidden input
                checkboxes.click(function() {
                  var newValue = [];

                  checkboxes.each(function() {
                    if ($(this).is(':checked')) {
                      var id = $(this).val();
                      newValue.push(id);
                    }
                  });

                  hidden_input.val(JSON.stringify(newValue)).trigger('change');
                });

                hidden_input.on('CrudField:disable', function(e) {
                      checkboxes.attr('disabled', 'disabled');
                  });

                hidden_input.on('CrudField:enable', function(e) {
                    checkboxes.removeAttr('disabled');
                });
            }
        </script>
        @endBassetBlock
    @endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}