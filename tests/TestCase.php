<?php

namespace SMartins\Exceptions\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SMartins\Exceptions\JsonHandlerServiceProvider;
use SMartins\Exceptions\Tests\Fixtures\Exceptions\Handler;

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
            ExceptionHandler::class,
            Handler::class
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
