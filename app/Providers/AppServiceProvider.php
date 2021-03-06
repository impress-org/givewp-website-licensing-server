<?php

namespace App\Providers;

use App\Repositories\Addons;
use App\Repositories\GiveWP;
use App\Repositories\Licenses;
use App\Repositories\Subscriptions;
use App\Repositories\SystemOptions;
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
        $this->app->singleton(Licenses::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(Licenses::class);
        });

        $this->app->singleton(Addons::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(Addons::class);
        });

        $this->app->singleton(Subscriptions::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(Subscriptions::class);
        });

        $this->app->singleton(GiveWP::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(GiveWP::class);
        });

        $this->app->singleton(SystemOptions::class, function ($app) {
            /* @var \Laravel\Lumen\Application $app */
            return $app->make(SystemOptions::class);
        });
    }
}
