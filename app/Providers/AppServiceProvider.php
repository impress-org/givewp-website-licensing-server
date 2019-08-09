<?php

namespace App\Providers;

use App\Repositories\Addons;
use App\Repositories\Licenses;
use App\Repositories\Subscriptions;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( Licenses::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(Licenses::class);
        });

        $this->app->singleton( Addons::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(Addons::class);
        });

        $this->app->singleton( Subscriptions::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(Subscriptions::class);
        });
    }
}
