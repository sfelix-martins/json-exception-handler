<?php

namespace SMartins\Exceptions\Tests;

use SMartins\Exceptions\JsonHandlerServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \SMartins\Exceptions\Tests\Fixtures\Exceptions\Handler::class
        );

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'exceptions');
        $app['config']->set('database.connections.exceptions', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            JsonHandlerServiceProvider::class,
        ];
    }
}
