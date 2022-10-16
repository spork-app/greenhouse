<?php

namespace Spork\Greenhouse\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Spork\Greenhouse\PlantServiceProvider.php;

class TestCase extends TestbenchTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spork\\Greenhouse\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            PlantServiceProvider.php::class,
        ];
    }
}

