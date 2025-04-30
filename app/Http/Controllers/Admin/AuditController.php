<?php

namespace App\Http\Controllers\Admin;

use App\Models\Audit; // Use your custom model
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Backpack\Operations\AuditOperation;

class AuditController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    
    use AuditOperation;

    /**
     * Configure the CrudPanel object.
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Audit::class); // Use the fully qualified class name
        CRUD::setRoute(config('backpack.base.route_prefix') . '/audit');
        CRUD::setEntityNameStrings('audit', 'audits');
        
        // Set up the operation
        $this->setupAuditOperation();
    }
}