@if ($crud->hasAccess('restore', $entry) && $entry->trashed())
	<a href="javascript:void(0)" onclick="restoreEntry(this)" bp-button="restore" 
    data-route="{{ url($crud->route.'/'.$entry->getKey().'/restore') }}"  data-toggle="tooltip" 
        title="{{ trans('backpack/pro::trash.restore') }}"
	class="btn btn-icon btn-outline-secondary btn-pill" data-button-type="restore">
		<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-restore"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.06 13a9 9 0 1 0 .49 -4.087" /><path d="M3 4.001v5h5" /><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
		
	</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@loadOnce('restore_button_script')
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof restoreEntry != 'function') {
	  $("[data-button-type=restore]").unbind('click');

	  function restoreEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');

		swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "{!! trans('backpack/pro::trash.restore_confirm') !!}",
		  icon: "warning",
		  buttons: {
		  	cancel: {
				text: "{!! trans('backpack::crud.cancel') !!}",
				value: null,
				visible: true,
				className: "bg-secondary",
				closeModal: true,
			},
			delete: {
				text: "{!! trans('backpack/pro::trash.restore') !!}",
				value: true,
				visible: true,
				className: "bg-info",
				},
			},
		  dangerMode: true,
		}).then((value) => {
			if (value) {
				$.ajax({
			      url: route,
			      type: 'PUT',
			      success: function(result) {
			          if (result == 1) {
						  // Redraw the table
						  if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
							  // Move to previous page in case of deleting the only item in table
							  if(crud.table.rows().count() === 1) {
							    crud.table.page("previous");
							  }

							  crud.table.draw(false);
						  }

			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: "{!! '<strong>'.trans('backpack/pro::trash.restore_success_title').'</strong><br>'.trans('backpack/pro::trash.restore_success_message') !!}"
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');
			          } else {
			              // if the result is an array, it means
			              // we have notification bubbles to show
			          	  if (result instanceof Object) {
			          	  	// trigger one or more bubble notifications
			          	  	Object.entries(result).forEach(function(entry, index) {
			          	  	  var type = entry[0];
			          	  	  entry[1].forEach(function(message, i) {
					          	  new Noty({
				                    type: type,
				                    text: message
				                  }).show();
			          	  	  });
			          	  	});
			          	  } else {// Show an error alert
				              swal({
				              	title: "{!! trans('backpack/pro::trash.restore_error_title') !!}",
	                            text: "{!! trans('backpack/pro::trash.restore_error_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	buttons: false,
				              });
			          	  }
			          }
			      },
			      error: function(result) {
			          // Show an alert with the result
			          swal({
		              	title: "{!! trans('backpack/pro::trash.restore_error_title') !!}",
                        text: "{!! trans('backpack/pro::trash.restore_error_message') !!}",
		              	icon: "error",
		              	timer: 4000,
		              	buttons: false,
		              });
			      }
			  });
			}
		});

      }
	}

</script>
@if (!request()->ajax()) @endpush @endif
@endLoadOnce