<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ValidationIconHelper;
use App\Http\Requests\TaxRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\MyCrudController;


class TaxCrudController extends MyCrudController
{
    use \Backpack\ReviseOperation\ReviseOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CloneOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkCloneOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Tax::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/tax');
        CRUD::setEntityNameStrings('tax', 'taxes');

        $this->setupPermissions();

        // Set the action column styling
        $this->setActionsColumnWidth('320px');
        $this->setActionsHeaderAlign('center');
        $this->setActionsCellAlign('center');
    }

    /**
     * Setup permissions for CRUD operations
     */
    protected function setupPermissions()
    {
        // Check permissions before proceeding
        if (!backpack_user()->can('Tax_Read') && !backpack_user()->hasRole('Administrator')) {
            // This prevents access to any operation
            abort(403, 'You do not have permission to access this page.');
        }

        // Check if user has Read permission or is Administrator
        if (!backpack_user()->can('Tax_Read') && !backpack_user()->hasRole('Administrator')) {
            $this->crud->denyAccess(['list', 'show']);
        }

        // Check Create permission
        if (!backpack_user()->can('Tax_Create') && !backpack_user()->hasRole('Administrator')) {
            $this->crud->denyAccess(['create', 'clone', 'bulkClone']);
        }

        // Check Update permission
        if (!backpack_user()->can('Tax_Update') && !backpack_user()->hasRole('Administrator')) {
            $this->crud->denyAccess('update');
        }

        // Check Delete permission
        if (!backpack_user()->can('Tax_Delete') && !backpack_user()->hasRole('Administrator')) {
            $this->crud->denyAccess(['delete', 'bulkDelete']);
        }
    }



    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Add columns for list view

        $this->crud->addColumn([
            'name' => 'TaxID',
            'label' => 'ID',
            'type' => 'text',
            'width' => '70px',
            'headerAlign' => 'left',
            'cellAlign' => 'right'
        ]);

        CRUD::column('TaxName')->label('Tax Name');

        $this->crud->addColumn([
            'name' => 'TaxName',
            'label' => 'Tax Names',
            'type' => 'text',
            'width' => '160px',
            'headerAlign' => 'left',
            'cellAlign' => 'left'
        ]);

        // Add Tax Rate column with right alignment
        CRUD::addColumn([
            'name' => 'TaxRate',
            'label' => 'Tax Rate (%)',
            'type' => 'number',
            'decimals' => 2,
            'prefix' => '',
            'suffix' => '%',
            // New properties:
            'width' => '70px',           // Set column width
            'headerAlign' => 'right',    // Align header text (left, center, right)
            'cellAlign' => 'right'        // Align cell content (left, center, right)
        ]);

        // Add validity column with SVG icons using the helper and right alignment
        CRUD::addColumn([
            'name' => 'valid',
            'label' => 'Valid',
            'type' => 'custom_html',
            'value' => function ($entry) {
                return ValidationIconHelper::getValidationIcon($entry->isValid());
            },
            'width' => '50px',
            'headerAlign' => 'center',
            'cellAlign' => 'center',
            'orderable' => false,
            'searchLogic' => false,
            'visibleInExport' => true,
        ]);

        $this->crud->addColumn([
            'name' => 'is_valid_row_class',
            'visibleInTable' => false,
            'visibleInModal' => false,
            'visibleInExport' => false,
            'visibleInShow' => false,
            'type'      => 'text', // Add the 'type'
            'value'     => function ($entry) {
                return !$entry->isValid() ? 'invalid-row-check' : 'valid-row-check';
            },
            'searchLogic' => false, // Prevent searching on this dummy column
            'orderable'   => false, // Prevent ordering on this dummy column
        ]);

        // Enable responsive table
        $this->crud->enableResponsiveTable();

        // Setup export button for those with permission
        if (backpack_user()->can('Tax_Print') || backpack_user()->hasRole('Administrator')) {
            $this->crud->enableExportButtons();
        }

        // Enable the select all checkbox and bulk actions
        $this->crud->enablePersistentTable();

        // Add pagination controls
        //$this->crud->setPaginationSettings(['pageLength' => 10, 'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']]]);

        // Setup filters with debounce
        $this->crud->addFilter(
            [
                'name' => 'TaxName',
                'type' => 'text',
                'label' => 'Tax Name'
            ],
            false,
            function ($value) {
                $this->crud->addClause('where', 'TaxName', 'LIKE', "%$value%");
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'TaxRate',
                'type' => 'range',
                'label' => 'Tax Rate',
                'label_from' => 'Min value',
                'label_to' => 'Max value'
            ],
            false,
            function ($value) {
                if (isset($value['from'])) {
                    $this->crud->addClause('where', 'TaxRate', '>=', (float) $value['from']);
                }
                if (isset($value['to'])) {
                    $this->crud->addClause('where', 'TaxRate', '<=', (float) $value['to']);
                }
            }
        );

        // Add filter to show only invalid records
        $this->crud->addFilter(
            [
                'type' => 'simple',
                'name' => 'show_invalid',
                'label' => 'Show Invalid Records'
            ],
            false,
            function () {
                // This is a placeholder - since we're setting SHASignature to "test" when 
                // saving, we'll need a different query here in a real implementation
                $this->crud->addClause('where', 'SHASignature', '!=', function ($query) {
                    $query->selectRaw("SHA2(CONCAT_WS('|$$|', `TaxName`, `TaxRate`), 512)");
                });
            }
        );

        // Add search with debounce
        //   $this->crud->enableSearchPersistence(1000);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(TaxRequest::class);

        // Add fields for create form
        CRUD::field('TaxName')
            ->label('Tax Name')
            ->type('text')
            ->attributes(['maxlength' => 20])
            ->hint('Enter the tax name or description (e.g., VAT, GST, Sales Tax)');

        CRUD::field('TaxRate')
            ->label('Tax Rate (%)')
            ->type('number')
            ->attributes(['step' => '0.01', 'min' => '0', 'max' => '100'])
            ->hint('Enter the tax rate percentage (e.g., 18.00 for 18%)');
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Show operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-show
     * @return void
     */
    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
