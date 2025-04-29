<?php

namespace App\Backpack\Operations;

use App\Services\Security;
use App\Utils\SvgIcons;
use Illuminate\Support\Facades\DB;

trait AuditOperation
{
    /**
     * Define what happens when the operation is accessed
     */
    protected function setupAuditOperation()
    {
        // Skip button and action management entirely
        // Just configure the CRUD without trying to remove buttons/actions
        
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/audit');
        $this->crud->setEntityNameStrings('audit', 'audits');
        
        // Set columns
        $this->crud->setColumns([
            [
                'name' => 'id',
                'label' => 'ID',
                'type' => 'number',
            ],
            [
                'name' => 'auditable_type',
                'label' => 'Model',
            ],
            [
                'name' => 'auditable_id',
                'label' => 'ID',
            ],
            [
                'name' => 'event',
                'label' => 'Event',
            ],
            [
                'name' => 'user_id',
                'label' => 'User',
                'type' => 'relationship',
                'entity' => 'user',
                'attribute' => 'name',
            ],
            [
                'name' => 'created_at',
                'label' => 'Time',
                'type' => 'datetime',
            ],
            [
                'name' => 'data_integrity',
                'label' => 'Integrity',
                'type' => 'custom_html',
                'value' => function($entry) {
                    $auditData = [
                        'user_type' => $entry->user_type,
                        'user_id' => $entry->user_id,
                        'event' => $entry->event,
                        'auditable_type' => $entry->auditable_type,
                        'auditable_id' => $entry->auditable_id,
                        'old_values' => json_encode($entry->old_values),
                        'new_values' => json_encode($entry->new_values),
                        'url' => $entry->url,
                        'ip_address' => $entry->ip_address,
                        'user_agent' => $entry->user_agent,
                        'tags' => $entry->tags,
                    ];
                    
                    $isValid = Security::checkData($auditData, $entry->SHASignature);
                    
                    return SvgIcons::integrityIcon($isValid);
                }
            ],
        ]);
        
        // Add filters
        $this->crud->addFilter([
            'name' => 'auditable_type',
            'type' => 'dropdown',
            'label' => 'Model'
        ], function() {
            // Get distinct auditable types from the database
            return DB::table('audits')
                ->select('auditable_type')
                ->distinct()
                ->pluck('auditable_type', 'auditable_type')
                ->toArray();
        }, function($value) {
            $this->crud->addClause('where', 'auditable_type', $value);
        });
        
        $this->crud->addFilter([
            'name' => 'event',
            'type' => 'dropdown',
            'label' => 'Event'
        ], [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
        ], function($value) {
            $this->crud->addClause('where', 'event', $value);
        });
        
        $this->crud->addFilter([
            'name' => 'user_id',
            'type' => 'select2',
            'label' => 'User'
        ], function() {
            return \App\Models\User::all()->pluck('name', 'id')->toArray();
        }, function($value) {
            $this->crud->addClause('where', 'user_id', $value);
        });
    }
    
    /**
     * Get the audit logs for a specific model
     */
    public function getModelAudits($modelType, $modelId)
    {
        // Set up the operation
        $this->setupAuditOperation();
        
        // Filter by model type and ID
        $this->crud->addClause('where', 'auditable_type', $modelType);
        $this->crud->addClause('where', 'auditable_id', $modelId);
        
        // Render the list view
        return view('crud::operations.list', [
            'crud' => $this->crud,
            'title' => 'Audit Logs for ' . class_basename($modelType) . ' #' . $modelId,
        ]);
    }
}