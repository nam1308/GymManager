<?php

namespace App\Providers;

use App\Facades\PurchasesService;
use App\Models\Admin;
use App\Services\CommunitiesService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('CommunitiesService', CommunitiesService::class);
        $this->app->bind('PurchasesService', PurchasesService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Cashier::useCustomerModel(Admin::class);
        Paginator::useBootstrap();
    }
}
