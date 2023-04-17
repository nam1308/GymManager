<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PurchasesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'PurchasesService',
            'App\Services\PurchasesService'
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
