<?php

namespace Inktweb\Bolcom\RetailerApi\Laravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        $this->publishes(
            [
                __DIR__ . '/../config/bolcom-retailer-api.php' => $this->app->configPath('bolcom-retailer-api.php'),
            ],
            'bolcom-retailer-api'
        );
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bolcom-retailer-api.php', 'bolcom-retailer-api');

        $this->app->singleton(
            'bolcom-retailer-api',
            function (Application $app) {
                Facade::setFacadeApplication($app);

                $config = $app->make('config')->get('bolcom-retailer-api');
                $defaultAccount = $config['client']['default-account'];

                return Facade::account($defaultAccount);
            }
        );
    }
}
