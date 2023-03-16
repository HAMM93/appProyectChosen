<?php

namespace App\Providers;


use App\Services\Logging\Log;
use Illuminate\Support\ServiceProvider;

class LoggingServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('logging', function () {
            return new Log();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['logging'];
    }

}
