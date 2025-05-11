<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AuditOwenRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


/**
 * Class AuditOwenCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AuditOwenCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\AuditOwen::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/system-audit');
        CRUD::setEntityNameStrings('System Audit', 'System Audit');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AuditOwenRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    public function setupListOperation()
    {
        // Set the model and route for the CRUD
        CRUD::setModel(\App\Models\AuditOwen::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/system-audit');
        CRUD::setEntityNameStrings('System Audit', 'System Audit');

        // Configure columns
        $this->crud->set('show.setFromDb', false);
        $this->crud->operation('list', function () {
            $this->crud->removeAllColumns(); // Start fresh
        });

        // Basic columns
        CRUD::column([
            'name' => 'created_at',
            'label' => 'Timestamp',
            'type' => 'text',
            'value' => function ($entry) {
                return $entry->created_at->format('d M Y, H:i');
            }
        ]);

        CRUD::column([
            'name' => 'event',
            'label' => 'Event',
            'type' => 'custom_html',
            'value' => function ($entry) {
                $colors = [
                    'created' => 'success',
                    'updated' => 'info',
                    'deleted' => 'danger',
                    'restored' => 'warning'
                ];
                $color = $colors[$entry->event] ?? 'secondary';
                return '<span class="badge badge-' . $color . '">' . ucfirst($entry->event) . '</span>';
            }
        ]);

        CRUD::column([
            'name' => 'user_id',
            'label' => 'User',
            'type' => 'custom_html',
            'value' => function ($entry) {
                return $entry->user ?
                    '<i class="la la-user"></i> ' . e($entry->user->name) :
                    '<i class="la la-robot"></i> System';
            }
        ]);

        CRUD::column([
            'name' => 'model',
            'label' => 'Model',
            'type' => 'text',
            'value' => function ($entry) {
                return class_basename($entry->auditable_type) . ' #' . $entry->auditable_id;
            }
        ]);

        CRUD::column([
            'name' => 'integrity',
            'label' => 'Integrity',
            'type' => 'custom_html',
            'value' => function ($entry) {
                return '<span class="badge badge-secondary">No Signature</span>';
            }
        ]);

        // Old Values column - with improved stacked formatting
        CRUD::column([
            'name' => 'old_values',
            'label' => 'Old Values',
            'type' => 'custom_html',
            'value' => function ($entry) {
                $values = json_decode($entry->old_values, true);

                if (empty($values)) {
                    return '<span class="text-muted">None</span>';
                }

                $html = '<div style="max-width: 300px;">';
                foreach ($values as $key => $value) {
                    $valueStr = is_array($value) ? json_encode($value) : (is_null($value) ? 'null' : (string)$value);

                    // Truncate if necessary
                    if (strlen($valueStr) > 30) {
                        $valueStr = substr($valueStr, 0, 30) . '...';
                    }

                    $html .= '<div class="mb-2">';
                    $html .= '<div class="font-weight-bold text-muted small">' . e($key) . ':</div>';
                    $html .= '<div class="text-danger">' . e($valueStr) . '</div>';
                    $html .= '</div>';
                }
                $html .= '</div>';

                return $html;
            }
        ]);

        // New Values column - with improved stacked formatting
        CRUD::column([
            'name' => 'new_values',
            'label' => 'New Values',
            'type' => 'custom_html',
            'value' => function ($entry) {
                $values = json_decode($entry->new_values, true);

                if (empty($values)) {
                    return '<span class="text-muted">None</span>';
                }

                $html = '<div style="max-width: 300px;">';
                foreach ($values as $key => $value) {
                    $valueStr = is_array($value) ? json_encode($value) : (is_null($value) ? 'null' : (string)$value);

                    // Truncate if necessary
                    if (strlen($valueStr) > 30) {
                        $valueStr = substr($valueStr, 0, 30) . '...';
                    }

                    $html .= '<div class="mb-2">';
                    $html .= '<div class="font-weight-bold text-muted small">' . e($key) . ':</div>';
                    $html .= '<div class="text-primary">' . e($valueStr) . '</div>';
                    $html .= '</div>';
                }
                $html .= '</div>';

                return $html;
            }
        ]);

        // Add Preview button
        CRUD::column([
            'name' => 'actions',
            'label' => 'Actions',
            'type' => 'custom_html',
            'value' => function ($entry) {
                return '<a href="' . backpack_url('system-audit/' . $entry->id . '/show') . '" class="btn btn-sm btn-outline-success btn-pill"><i class="la la-eye"></i> Preview</a>';
            }
        ]);

        // Add filters
        CRUD::addFilter([
            'name' => 'event',
            'type' => 'dropdown',
            'label' => 'Event Type'
        ], [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored'
        ], function ($value) {
            CRUD::addClause('where', 'event', $value);
        });

        CRUD::addFilter(
            [
                'type' => 'date_range',
                'name' => 'created_at',
                'label' => 'Date Range'
            ],
            false,
            function ($value) {
                $dates = json_decode($value);
                CRUD::addClause('where', 'created_at', '>=', $dates->from);
                CRUD::addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            }
        );

        // No standard buttons needed
        CRUD::removeAllButtons();

        // Settings for better display
        CRUD::setDefaultPageLength(10);
        CRUD::enableExportButtons();
        CRUD::setOperationSetting('responsiveTable', false); // Disable responsive table to show all columns
    }


    public function setupShowOperation()
    {
        // Set the model and route
        CRUD::setModel(\App\Models\AuditOwen::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/system-audit');
        CRUD::setEntityNameStrings('System Audit', 'System Audit');

        // Remove default buttons
        CRUD::removeAllButtons();

        // Add a proper back button using standard Backpack button
        CRUD::addButton('top', 'back_to_list', 'view', 'backpack::buttons.back', 'end');

        // Basic fields
        CRUD::addColumn([
            'name' => 'id',
            'label' => 'ID',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'label' => 'Timestamp',
            'type' => 'datetime',
            'format' => 'D MMM Y, HH:mm:ss',
        ]);

        CRUD::addColumn([
            'name' => 'event',
            'label' => 'Event',
            'type' => 'custom_html',
            'value' => function ($entry) {
                $colors = [
                    'created' => 'success',
                    'updated' => 'info',
                    'deleted' => 'danger',
                    'restored' => 'warning'
                ];
                $color = $colors[$entry->event] ?? 'secondary';
                return '<span class="badge badge-' . $color . '">' . ucfirst($entry->event) . '</span>';
            }
        ]);

        // User who performed the action
        CRUD::addColumn([
            'name' => 'user_id',
            'label' => 'User',
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->user ?
                    '<i class="la la-user"></i> ' . e($entry->user->name) :
                    '<i class="la la-robot"></i> System/Guest';
            },
            'escaped' => false,
        ]);

        // Auditable Model Type and ID
        CRUD::addColumn([
            'name' => 'auditable_type',
            'label' => 'Model Type',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'auditable_id',
            'label' => 'Model ID',
            'type' => 'text',
        ]);

        // Old values (table format)
        CRUD::addColumn([
            'name' => 'old_values',
            'label' => 'Old Values',
            'type' => 'closure',
            'function' => function ($entry) {
                $values = json_decode($entry->old_values, true);
                if (empty($values)) {
                    return '<span class="text-muted">No old values (e.g., created event)</span>';
                }
                $output = '<table class="table table-bordered table-sm"><thead><tr><th style="width: 15%;">Attribute</th><th>Value</th></tr></thead><tbody>';
                foreach ($values as $key => $value) {
                    $displayValue = is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
                    // Handle null values
                    $displayValue = is_null($displayValue) ? '<em class="text-muted">null</em>' : e($displayValue);
                    $output .= "<tr><td class='font-weight-bold'>{$key}</td><td><pre style='white-space: pre-wrap; word-break: break-all; margin: 0;'>{$displayValue}</pre></td></tr>";
                }
                $output .= '</tbody></table>';
                return $output;
            },
            'escaped' => false,
        ]);

        // New values (table format)
        CRUD::addColumn([
            'name' => 'new_values',
            'label' => 'New Values',
            'type' => 'closure',
            'function' => function ($entry) {
                $values = json_decode($entry->new_values, true);
                if (empty($values)) {
                    return '<span class="text-muted">No new values (e.g., deleted event)</span>';
                }
                $output = '<table class="table table-bordered table-sm"><thead><tr><th style="width: 15%;">Attribute</th><th>Value</th></tr></thead><tbody>';
                foreach ($values as $key => $value) {
                    $displayValue = is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
                    // Handle null values
                    $displayValue = is_null($displayValue) ? '<em class="text-muted">null</em>' : e($displayValue);
                    $output .= "<tr><td class='font-weight-bold'>{$key}</td><td><pre style='white-space: pre-wrap; word-break: break-all; margin: 0;'>{$displayValue}</pre></td></tr>";
                }
                $output .= '</tbody></table>';
                return $output;
            },
            'escaped' => false,
        ]);

        // URL - Let's try with raw output type
        CRUD::addColumn([
            'name' => 'url',
            'label' => 'URL',
            'type' => 'closure',
            'function' => function ($entry) {
                if (empty($entry->url)) {
                    return '<em class="text-muted">No URL data available</em>';
                }
                return  e($entry->url);
            },
            'escaped' => false,
        ]);

        // IP Address
        CRUD::addColumn([
            'name' => 'ip_address',
            'label' => 'IP Address',
            'type' => 'text',
        ]);

        // User Agent - Let's try with raw output type
        CRUD::addColumn([
            'name' => 'user_agent',
            'label' => 'User Agent',
            'type' => 'closure',
            'function' => function ($entry) {
                if (empty($entry->user_agent)) {
                    return '<em class="text-muted">No User Agent data available</em>';
                }
                return  e($entry->user_agent);
            },
            'escaped' => false,
        ]);
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
}
