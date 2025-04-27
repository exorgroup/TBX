{{-- dependencyJson --}}
@php
  $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
  $field['wrapper']['class'] = $field['wrapper']['class'] ?? 'form-group col-sm-12';
  $field['wrapper']['class'] = $field['wrapper']['class'].' checklist_dependency';
  $field['wrapper']['data-entity'] = $field['wrapper']['data-entity'] ?? $field['field_unique_name'];
  $field['wrapper']['data-init-function'] = $field['wrapper']['init-function'] ?? 'bpFieldInitChecklistDependencyElement';
@endphp

@include('crud::fields.inc.wrapper_start')

    <label><h3>{!! $field['label'] !!}</h3></label>
    <?php
      $entity_model = $crud->getModel();

      //short name for dependency fields
      $primary_dependency = $field['subfields']['primary'];
      $secondary_dependency = $field['subfields']['secondary'];

      //all items with relation
      $dependencies = $primary_dependency['model']::with($primary_dependency['entity_secondary'])->get();

      $dependencyArray = [];

      //convert dependency array to simple matrix ( primary id as key and array with secondaries id )
      foreach ($dependencies as $primary) {
          $dependencyArray[$primary->id] = [];
          foreach ($primary->{$primary_dependency['entity_secondary']} as $secondary) {
              $dependencyArray[$primary->id][] = $secondary->id;
          }
      }

      $old_primary_dependency = old_empty_or_null($primary_dependency['name'], false) ?? false;
      $old_secondary_dependency = old_empty_or_null($secondary_dependency['name'], false) ?? false;

      //for update form, get initial state of the entity
      if (isset($id) && $id) {
          //get entity with relations for primary dependency
          $entity_dependencies = $entity_model->with($primary_dependency['entity'])
          ->with($primary_dependency['entity'].'.'.$primary_dependency['entity_secondary'])
          ->find($id);

          $secondaries_from_primary = [];

          //convert relation in array
          $primary_array = $entity_dependencies->{$primary_dependency['entity']}->toArray();

          $secondary_ids = [];
          //create secondary dependency from primary relation, used to check what checkbox must be checked from second checklist
          if ($old_primary_dependency) {
              foreach ($old_primary_dependency as $primary_item) {
                  foreach ($dependencyArray[$primary_item] as $second_item) {
                      $secondary_ids[$second_item] = $second_item;
                  }
              }
          } else { //create dependencies from relation if not from validate error
              foreach ($primary_array as $primary_item) {
                  foreach ($primary_item[$secondary_dependency['entity']] as $second_item) {
                      $secondary_ids[$second_item['id']] = $second_item['id'];
                  }
              }
          }
      }

      //json encode of dependency matrix
      $dependencyJson = json_encode($dependencyArray);
    ?>

    <div class="container p-0">
    <!-- Roles section -->
      <div class="row">
          <div class="col-sm-12">
              <label class="font-weight-bold">{!! $primary_dependency['label'] !!}</label>
              @include('crud::fields.inc.translatable_icon', ['field' => $primary_dependency])
          </div>
      </div>

    
      <div class="row">
            <!-- hidden roles-->
          <div class="hidden_fields_primary" data-name = "{{ $primary_dependency['name'] }}">
          <input type="hidden" bp-field-name="{{$primary_dependency['name']}}" name="{{$primary_dependency['name']}}" value="" />
          @if(isset($field['value']))
              @if($old_primary_dependency)
                  @foreach($old_primary_dependency as $item )
                  <input type="hidden" class="primary_hidden" name="{{ $primary_dependency['name'] }}[]" value="{{ $item }}">
                  @endforeach
              @else
                  @foreach( $field['value'][0]->pluck('id', 'id')->toArray() as $item )
                  <input type="hidden" class="primary_hidden" name="{{ $primary_dependency['name'] }}[]" value="{{ $item }}">
                  @endforeach
              @endif
            @endif
          </div>
        <!-- end hidden roles -->

      @foreach ($primary_dependency['model']::all() as $connected_entity_entry)
          <div class="col-sm-{{ isset($primary_dependency['number_columns']) ? intval(12/$primary_dependency['number_columns']) : '4'}}">
              <div class="checkbox">
                  <label class="font-weight-normal">
                      <input type="checkbox"
                          data-id = "{{ $connected_entity_entry->id }}"
                          class = 'primary_list'
                          @foreach ($primary_dependency as $attribute => $value)
                              @if (is_string($attribute) && $attribute != 'value')
                                  @if ($attribute=='name')
                                  {{ $attribute }}="{{ $value }}_show[]"
                                  @else
                                  {{ $attribute }}="{{ $value }}"
                                  @endif
                              @endif
                          @endforeach
                          value="{{ $connected_entity_entry->id }}"

                          @if( ( isset($field['value']) && is_array($field['value']) && in_array($connected_entity_entry->id, $field['value'][0]->pluck('id', 'id')->toArray())) || $old_primary_dependency && in_array($connected_entity_entry->id, $old_primary_dependency))
                          checked = "checked"
                          @endif >
                          {{ $connected_entity_entry->{$primary_dependency['attribute']} }}
                  </label>
              </div>
          </div>
      @endforeach
      </div>

      <!--Permissions Section -->
      <div class="row mt-4">
          <div class="col-sm-12">
              <label class="font-weight-bold">{!! $secondary_dependency['label'] !!}</label>
              @include('crud::fields.inc.translatable_icon', ['field' => $secondary_dependency])
          </div>
      </div>

      <div class="card">
        <div class="card-header py-2">
          <div class="col-sm-12 my-2">
            <ul class="list-group list-group-horizontal flex-wrap">
              <li class="list-group-item" style="padding: 2px 10px; color:grey; border:0px;">
                navigation
              </li>
              @foreach(range('a', 'z') as $letter)
                <li class="list-group-item" style="padding: 2px 10px; border-top:0px; border-bottom:0px; @if($letter == 'a') border-left:0px; @endif @if($letter == 'z') border-right:0px; @endif">
                  <a href="#{{ $letter }}">{{ $letter }}</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="card-body p-0">
          <!-- hidden secondary permissions-->
          <div class="hidden_fields_secondary" data-name="{{ $secondary_dependency['name'] }}">
            <input type="hidden" bp-field-name="{{$secondary_dependency['name']}}" name="{{$secondary_dependency['name']}}" value="" />
            @if(isset($field['value']))
              @if($old_secondary_dependency)
                @foreach($old_secondary_dependency as $item )
                  <input type="hidden" class="secondary_hidden" name="{{ $secondary_dependency['name'] }}[]" value="{{ $item }}">
                @endforeach
              @else
                @foreach( $field['value'][1]->pluck('id', 'id')->toArray() as $item )
                  <input type="hidden" class="secondary_hidden" name="{{ $secondary_dependency['name'] }}[]" value="{{ $item }}">
                @endforeach
              @endif
            @endif
          </div>
          <!-- end secondary hidden permissions -->

          @php
            //sort values
            $array = $secondary_dependency['model']::all();
            $length = count($array);
            for ($i = 0; $i < $length; $i++) {
              for ($j = $i + 1; $j < $length; $j++) {
                if ($array->toArray()[$i]['name'] > $array->toArray()[$j]['name']) {
                  $temp = $array[$i];
                  $array[$i] = $array[$j];
                  $array[$j] = $temp;
                }
              }
            }
            $currTitle = "";
            $isFirstRow = true;
            $currLetter = "";
          @endphp

          <div class="list-group list-group-flush overflow-auto" style="max-height: 35rem">
            @foreach ($array as $connected_entity_entry)
              @php
                $title = explode('_', $connected_entity_entry->{$secondary_dependency['attribute']});
                $permission = substr($connected_entity_entry->{$secondary_dependency['attribute']}, strlen($title[0])+1);
                $icon = '';
                $tooltipTitle = '';
                
                switch($permission) {
                  case 'View':
                  case 'Read':
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>';
                    $tooltipTitle = 'read';
                    break;
                  case 'New':
                  case 'Add':
                  case 'Create':
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M12 11l0 6" /><path d="M9 14l6 0" /></svg>';
                    $tooltipTitle = 'create';
                    break;
                  case 'Update':
                  case 'Edit':
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>';
                    $tooltipTitle = 'update';
                    break;
                  case 'Delete':
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>';
                    $tooltipTitle = 'delete';
                    break;
                  case 'Print':
                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>';
                    $tooltipTitle = 'print';
                    break;
                  default:
                    $icon = $permission;
                    $tooltipTitle = $permission;
                }
              @endphp

              @if ($title[0] != $currTitle)           
                @if (!$isFirstRow)              
                        </div>
                      </div>
                    </div>
                  </div>           
                @endif

                @php($firstLetter = strtolower(substr($title[0], 0, 1)))
                @if($firstLetter != $currLetter)
                  <div id="{{$firstLetter}}" class="list-group-header sticky-top">{{ strtoupper($firstLetter) }}</div>
                  @php($currLetter = $firstLetter)
                @endif

                @php($currTitle = $title[0])
                @php($isFirstRow = false)

                <div class="list-group-item">  
                  <div class="row">
                    <div class="d-flex align-items-center"> 
                      <div class="w-25 font-weight-bold" id="0{{strtolower(substr($currTitle,0,1))}}">{{ $currTitle }}</div>
                      <div class="w-75 form-selectgroup">
              @endif
                          
              <label class="permission-label">
                <input class="permission-checkbox secondary_list"
                  data-id="{{ $connected_entity_entry->id }}"
                  type="checkbox"
                  @foreach ($secondary_dependency as $attribute => $value)
                    @if (is_string($attribute) && $attribute != 'value')
                      @if ($attribute=='name')
                        {{ $attribute }}="{{ $value }}_show[]"
                      @else
                        {{ $attribute }}="{{ $value }}"
                      @endif
                    @endif
                  @endforeach
                  value="{{ $connected_entity_entry->id }}"
                  @if( ( isset($field['value']) && is_array($field['value']) && (  in_array($connected_entity_entry->id, $field['value'][1]->pluck('id', 'id')->toArray()) || isset( $secondary_ids[$connected_entity_entry->id])) || $old_secondary_dependency && in_array($connected_entity_entry->id, $old_secondary_dependency)))
                    checked="checked"
                    @if(isset( $secondary_ids[$connected_entity_entry->id]))
                      disabled="disabled"
                    @endif
                  @endif
                >
                <span class="permission-btn" data-toggle="tooltip" data-placement="bottom" title="{{ $tooltipTitle }}">
                  @if(in_array($permission, ['View', 'Read', 'New', 'Add', 'Create', 'Update', 'Edit', 'Delete', 'Print']))
                    {!! $icon !!}
                  @else
                    <span class="custom-permission-text">{{ $icon }}</span>
                  @endif
                  <span class="lock-icon"></span>
                </span>
              </label>
            @endforeach
            
            @if(!$isFirstRow)
                  </div>
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>{{-- /.container --}}

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
        padding-left: 0;
        padding-right: 5px;
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
      
      .icon-tabler {
        transition: all 0.2s ease;
      }
      
      .permission-checkbox:checked ~ .permission-btn .icon-tabler {
        stroke: #005500;
      }
      
      .permission-checkbox:not(:checked) ~ .permission-btn .icon-tabler {
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
  <script>
      var  {{ $field['field_unique_name'] }} = {!! $dependencyJson !!};
  </script>

  {{-- include checklist_dependency js --}}
  @bassetBlock('backpack/crud/fields/checklist-dependency-field.js')
    <script>
      function bpFieldInitChecklistDependencyElement(element) {

          var unique_name = element.data('entity');
          var dependencyJson = window[unique_name];
          var thisField = element;
          var handleCheckInput = function(el, field, dependencyJson) {
            let idCurrent = el.data('id');
            //add hidden field with this value
            let nameInput = field.find('.hidden_fields_primary').data('name');
            if(field.find('input.primary_hidden[value="'+idCurrent+'"]').length === 0) {
              let inputToAdd = $('<input type="hidden" class="primary_hidden" name="'+nameInput+'[]" value="'+idCurrent+'">');

              field.find('.hidden_fields_primary').append(inputToAdd);
              field.find('.hidden_fields_primary').find('input.primary_hidden[value="'+idCurrent+'"]').trigger('change');
            }
            $.each(dependencyJson[idCurrent], function(key, value){
              //check and disable secondies checkbox
              field.find('input.secondary_list[value="'+value+'"]').prop( "checked", true );
              field.find('input.secondary_list[value="'+value+'"]').prop( "disabled", true );
              field.find('input.secondary_list[value="'+value+'"]').attr('forced-select', 'true');
              //remove hidden fields with secondary dependency if was set
              var hidden = field.find('input.secondary_hidden[value="'+value+'"]');
              if(hidden)
                hidden.remove();
            });
          };
          
          thisField.find('div.hidden_fields_primary').children('input').first().on('CrudField:disable', function(e) {
              let input = $(e.target);
              input.parent().parent().find('input[type=checkbox]').attr('disabled', 'disabled');
              input.siblings('input').attr('disabled','disabled');
          });

          thisField.find('div.hidden_fields_primary').children('input').first().on('CrudField:enable', function(e) {
              let input = $(e.target);
              input.parent().parent().find('input[type=checkbox]').not('[forced-select]').removeAttr('disabled');
              input.siblings('input').removeAttr('disabled');
          });

          thisField.find('div.hidden_fields_secondary').children('input').first().on('CrudField:disable', function(e) {
              let input = $(e.target);
              input.parent().parent().find('input[type=checkbox]').attr('disabled', 'disabled');
              input.siblings('input').attr('disabled','disabled');
          });

          thisField.find('div.hidden_fields_secondary').children('input').first().on('CrudField:enable', function(e) {
              let input = $(e.target);
              input.parent().parent().find('input[type=checkbox]').not('[forced-select]').removeAttr('disabled');
              input.siblings('input').removeAttr('disabled');
          });

          thisField.find('.primary_list').each(function() {
            var checkbox = $(this);
            // re-check the secondary boxes in case the primary is re-checked from old.
            if(checkbox.is(':checked')){
               handleCheckInput(checkbox, thisField, dependencyJson);
            }
            // register the change event to handle subsquent checkbox state changes.
            checkbox.change(function(){
              if(checkbox.is(':checked')){
                handleCheckInput(checkbox, thisField, dependencyJson);
              }else{
                let idCurrent = checkbox.data('id');
                //remove hidden field with this value.
                thisField.find('input.primary_hidden[value="'+idCurrent+'"]').remove();

                // uncheck and active secondary checkboxs if are not in other selected primary.
                var secondary = dependencyJson[idCurrent];

                var selected = [];
                thisField.find('input.primary_hidden').each(function (index, input){
                  selected.push( $(this).val() );
                });

                $.each(secondary, function(index, secondaryItem){
                  var ok = 1;

                  $.each(selected, function(index2, selectedItem){
                    if( dependencyJson[selectedItem].indexOf(secondaryItem) != -1 ){
                      ok =0;
                    }
                  });

                  if(ok){
                    thisField.find('input.secondary_list[value="'+secondaryItem+'"]').prop('checked', false);
                    thisField.find('input.secondary_list[value="'+secondaryItem+'"]').prop('disabled', false);
                    thisField.find('input.secondary_list[value="'+secondaryItem+'"]').removeAttr('forced-select');
                  }
                });

              }
              });
          });


          thisField.find('.secondary_list').click(function(){

            var idCurrent = $(this).data('id');
            if($(this).is(':checked')){
              //add hidden field with this value
              var nameInput = thisField.find('.hidden_fields_secondary').data('name');
              var inputToAdd = $('<input type="hidden" class="secondary_hidden" name="'+nameInput+'[]" value="'+idCurrent+'">');

              thisField.find('.hidden_fields_secondary').append(inputToAdd);

            }else{
              //remove hidden field with this value
              thisField.find('input.secondary_hidden[value="'+idCurrent+'"]').remove();
            }
          });

      }
    </script>
  @endBassetBlock
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}