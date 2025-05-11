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
    // Only allow access to users with Tax_Read permission
    Route::group(['middleware' => ['can:Tax_Read']], function () {
        Route::crud('tax', 'TaxCrudController');
    });
    Route::crud('audit-owen', 'AuditOwenCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
