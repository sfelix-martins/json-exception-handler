<?php

namespace SMartins\Exceptions;

use Illuminate\Support\ServiceProvider;

class JsonHandlerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            $this->configPath() => config_path('json-exception-handler.php'),
        ]);

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'exception');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/exception'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'json-exception-handler');
    }

    public function configPath()
    {
        return __DIR__.'/../config/json-exception-handler.php';
    }
}
