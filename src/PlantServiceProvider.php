<?php

namespace Spork\Greenhouse;

use App\Spork;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PlantServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        Spork::addFeature('Greenhouse', 'SparklesIcon', '/greenhouse');

        Spork::actions('Greenhouse', __DIR__ . '/Actions');

        Route::middleware($this->app->make('config')->get('spork.greenhouse.middleware', ['auth:sanctum']))
            ->prefix('api/greenhouse')
            ->group(__DIR__ . '/../routes/web.php');
    }
}
