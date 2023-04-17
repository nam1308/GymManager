<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function (Admin $admin) {
            return $admin->role === config('const.ADMIN_ROLE.ADMIN.STATUS');
        });
        Gate::define('staff', function (Admin $admin) {
            return $admin->role === config('const.ADMIN_ROLE.STAFF.STATUS');
        });
    }
}
