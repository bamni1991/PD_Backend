<?php

namespace App\Modules\MyLife\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MyLifeServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind services here
    }

    public function boot()
    {
        $this->registerRoutes();

        // Load views from the module's Resources/Views directory
        $viewsPath = base_path('app/Modules/MyLife/Resources/Views');
        if (is_dir($viewsPath)) {
            $this->loadViewsFrom($viewsPath, 'mylife');
        }

        // Load migrations from the module's Database/Migrations directory
        $migrationsPath = base_path('app/Modules/MyLife/Database/Migrations');
        if (is_dir($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
    }

    protected function registerRoutes()
    {
        Route::middleware('web')
            ->namespace('App\\Modules\\MyLife\\Controllers')
            ->group(base_path('app/Modules/MyLife/Routes/web.php'));
    }
}
