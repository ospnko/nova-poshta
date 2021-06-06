<?php

namespace ScaryLayer\NovaPoshta\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use ScaryLayer\NovaPoshta\Commands\Load;

class ServiceProvider extends Provider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([Load::class]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/nova-poshta.php',
            'nova-poshta'
        );
    }
}