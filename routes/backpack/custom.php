<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
     // Override the original permission manager routes
    Route::crud('role', 'RoleCrudController_Extended');
    Route::crud('user', 'UserCrudController_Extended');
    Route::crud('audit', 'AuditController');
    Route::get('audit/{modelType}/{modelId}', 'AuditController@getModelAudits');    
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
