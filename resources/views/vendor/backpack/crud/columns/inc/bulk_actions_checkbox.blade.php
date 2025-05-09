@if (!isset($entry))
    <span class="crud_bulk_actions_checkbox">
        <input type="checkbox" class="crud_bulk_actions_general_checkbox form-check-input">
    </span>
@else
    <span class="crud_bulk_actions_checkbox">
        {{-- Check if the record is valid or user is admin --}}
        @if (!method_exists($entry, 'isValid') || $entry->isValid() || backpack_user()->hasRole('Administrator'))
            <input type="checkbox" class="crud_bulk_actions_line_checkbox form-check-input" data-primary-key-value="{{ $entry->getKey() }}">
        @else
            {{-- Disabled checkbox for invalid records (non-admin users) --}}
            <input type="checkbox" class="crud_bulk_actions_line_checkbox form-check-input disabled" disabled title="Only administrators can select invalid records" style="opacity: 0.6; cursor: not-allowed;">
        @endif
    </span>

    @bassetBlock('backpack/crud/operations/list/bulk-actions-checkbox.js')
    <script>
    if (typeof addOrRemoveCrudCheckedItem !== 'function') {
        function addOrRemoveCrudCheckedItem(element) {
            crud.lastCheckedItem = false;

            document.querySelectorAll('input.crud_bulk_actions_line_checkbox:not([disabled])').forEach(checkbox => checkbox.onclick = e => {
                e.stopPropagation();

                let checked = checkbox.checked;
                let primaryKeyValue = checkbox.dataset.primaryKeyValue;

                crud.checkedItems ??= [];
                
                if (checked) {
                    // add item to crud.checkedItems variable
                    crud.checkedItems.push(primaryKeyValue);

                    // if shift has been pressed, also select all elements
                    // between the last checked item and this one
                    if (crud.lastCheckedItem && e.shiftKey) {
                        let getNodeindex = elm => [...elm.parentNode.children].indexOf(elm);
                        let first = document.querySelector(`input.crud_bulk_actions_line_checkbox[data-primary-key-value="${crud.lastCheckedItem}"]`).closest('tr');
                        let last = document.querySelector(`input.crud_bulk_actions_line_checkbox[data-primary-key-value="${primaryKeyValue}"]`).closest('tr');
                        let firstIndex = getNodeindex(first);
                        let lastIndex = getNodeindex(last)
                        
                        while(first !== last) {
                            first = firstIndex < lastIndex ? first.nextElementSibling : first.previousElementSibling;
                            // Only check checkboxes that are not disabled
                            first.querySelector('input.crud_bulk_actions_line_checkbox:not(:checked):not([disabled])')?.click();
                        }
                    }

                    // remember that this one was the last checked item
                    crud.lastCheckedItem = primaryKeyValue;
                } else {
                    // remove item from crud.checkedItems variable
                    let index = crud.checkedItems.indexOf(primaryKeyValue);
                    if (index > -1) crud.checkedItems.splice(index, 1);
                }

                // if no items are selected, disable all bulk buttons
                enableOrDisableBulkButtons();
            });
        }
    }

    if (typeof markCheckboxAsCheckedIfPreviouslySelected !== 'function') {
        function markCheckboxAsCheckedIfPreviouslySelected() {
            let checkedItems = crud.checkedItems ?? [];
            let pageChanged = localStorage.getItem('page_changed') ?? false;
            let tableUrl = crud.table.ajax.url();
            let hasFilterApplied = false;

            if (tableUrl.indexOf('?') > -1) {
                if (tableUrl.substring(tableUrl.indexOf('?') + 1).length > 0) {
                    hasFilterApplied = true;
                }
            }

            // if it was not a page change, we check if datatables have any search, or the url have any parameters.
            // if you have filtered entries, and then remove the filters we are sure the entries are in the table.
            // we don't remove them in that case.
            if (! pageChanged && (crud.table.search().length !== 0 || hasFilterApplied)) {
                crud.checkedItems = [];
            }
            document
                .querySelectorAll('input.crud_bulk_actions_line_checkbox[data-primary-key-value]')
                .forEach(function(elem) {
                    let checked = checkedItems.length && checkedItems.indexOf(elem.dataset.primaryKeyValue) > -1;
                    elem.checked = checked;
                    if (checked && crud.checkedItems.indexOf(elem.dataset.primaryKeyValue) === -1) {
                        crud.checkedItems.push(elem.dataset.primaryKeyValue);
                    }
                });
            
            localStorage.removeItem('page_changed');
        }
    }

    if (typeof addBulkActionMainCheckboxesFunctionality !== 'function') {
        function addBulkActionMainCheckboxesFunctionality() {
            let mainCheckboxes = Array.from(document.querySelectorAll('input.crud_bulk_actions_general_checkbox'));
            let rowCheckboxes = Array.from(document.querySelectorAll('input.crud_bulk_actions_line_checkbox:not([disabled])'));

            mainCheckboxes.forEach(checkbox => {
                // set initial checked status - only count enabled checkboxes
                checkbox.checked = rowCheckboxes.length > 0 && 
                    document.querySelectorAll('input.crud_bulk_actions_line_checkbox:not([disabled]):not(:checked)').length === 0;

                // when the crud_bulk_actions_general_checkbox is selected, toggle all visible checkboxes that are not disabled
                checkbox.onclick = event => {
                    rowCheckboxes.filter(elem => checkbox.checked !== elem.checked).forEach(elem => elem.click());
                    
                    // make sure the other checkbox has the same checked status
                    mainCheckboxes.forEach(elem => elem.checked = checkbox.checked);

                    event.stopPropagation();
                }
            });

            // Stop propagation of href on the first column
            document.querySelectorAll('table td.dtr-control a').forEach(link => link.onclick = e => e.stopPropagation());
        }
    }

    if (typeof enableOrDisableBulkButtons !== 'function') {
        function enableOrDisableBulkButtons() {
            document.querySelectorAll('.bulk-button').forEach(btn => btn.classList.toggle('disabled', !crud.checkedItems?.length));
        }
    }

    // Highlight invalid rows for everyone, but only disable checkboxes for non-admins
    if (typeof highlightInvalidRows !== 'function') {
        function highlightInvalidRows() {
            document.querySelectorAll('#crudTable tbody tr').forEach(function(row) {
                // Check if this row contains the invalid-row-check marker
                if (row.innerHTML.indexOf('invalid-row-check') !== -1) {
                    // Add the invalid row class for styling
                    row.classList.add('row-invalid');
                } else {
                    // Ensure the row doesn't have the invalid class if it's valid
                    row.classList.remove('row-invalid');
                }
            });
        }
    }

    crud.addFunctionToDataTablesDrawEventQueue('addOrRemoveCrudCheckedItem');
    crud.addFunctionToDataTablesDrawEventQueue('markCheckboxAsCheckedIfPreviouslySelected');
    crud.addFunctionToDataTablesDrawEventQueue('addBulkActionMainCheckboxesFunctionality');
    crud.addFunctionToDataTablesDrawEventQueue('enableOrDisableBulkButtons');
    crud.addFunctionToDataTablesDrawEventQueue('highlightInvalidRows');
    </script>
    @endBassetBlock
@endif