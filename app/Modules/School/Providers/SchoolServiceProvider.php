<?php

namespace App\Modules\School\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SchoolServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind services here
    }

    public function boot()
    {
        $this->registerRoutes();

        // Load views from the module's Resources/Views directory
        $viewsPath = base_path('app/Modules/School/Resources/Views');
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'school');
        }

        // Load migrations from the module's Database/Migrations directory
        $migrationsPath = base_path('app/Modules/School/Database/Migrations');
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    protected function registerRoutes()
    {
        // Register API routes with 'api' middleware and prefix
        Route::middleware('api')
            ->prefix('api/school')
            ->namespace('App\\Modules\\School\\Controllers')
            ->group(base_path('app/Modules/School/Routes/api.php'));
    }
}
