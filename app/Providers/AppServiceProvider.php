<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {// Load custom routes after all other routes have been loaded
        $this->app->booted(function () {
            $this->replaceBackpackRoutes();
        });
    }

    /**
     * Load custom Backpack routes
     */
        protected function replaceBackpackRoutes()
    {
        // Get all registered routes
        $router = app('router');
        
        // Find and replace the backpack role and user routes
        $replacements = [
            'role' => 'App\Http\Controllers\Admin\RoleCrudController_Extended',
            'user' => 'App\Http\Controllers\Admin\UserCrudController_Extended',
            'permission' => 'App\Http\Controllers\Admin\PermissionCrudController_Extended'
        ];
        
        foreach ($router->getRoutes() as $route) {
            // Check if this is a backpack crud route we want to replace
            $routeName = $route->getName();
            $routeAction = $route->getAction();
            
            if (isset($routeAction['controller'])) {
                $controllerName = $routeAction['controller'];

                foreach ($replacements as $routeKey => $replacementController) {
                $originalControllerPattern = ucfirst($routeKey) . 'CrudController@';
                if (strpos($controllerName, $originalControllerPattern) !== false) {
                    // Replace with our custom controller
                    $newController = $replacementController . '@' . explode('@', $controllerName)[1];
                    $route->uses($newController);
                }
            }
                
            }
        }
    }
}
