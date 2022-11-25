<?php

namespace Spork\Greenhouse;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spork\Core\Spork;

class PlantServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
        Spork::addFeature('Greenhouse', 'SparklesIcon', '/greenhouse', 'crud');
        $this->mergeConfigFrom(__DIR__ . '/../config/spork.php', 'spork.greenhouse');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if (config('spork.greenhouse.enabled')) {
            Spork::actions('Greenhouse', __DIR__.'/Actions');

            Route::middleware($this->app->make('config')->get('spork.greenhouse.middleware', ['auth:sanctum']))
                ->prefix('api/greenhouse')
                ->group(__DIR__.'/../routes/web.php');
        }
    }
}
