<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CommunitiesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'CommunitiesService',
            'App\Services\CommunitiesService'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
