<?php

namespace App\Providers;

use App\Services\Payment\src\Payment;
use App\Services\Payment\src\PaymentManager;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Payment::class, function ($app) {
            return (new PaymentManager($app['config']))->resolve();
        });
    }
}
