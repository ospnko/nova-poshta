<?php

namespace ScaryLayer\NovaPoshta;

use Illuminate\Support\ServiceProvider as Provider;

class ServiceProvider extends Provider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([Command::class]);
        }
    }
}