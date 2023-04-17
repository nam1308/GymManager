<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request): ?string
    {
        //        if (! $request->expectsJson()) {
        //            return route('login');
        //        }
        if (!$request->expectsJson()) {
            if ($request->is('admin')) {
                return route('admin.login');
            }
            if ($request->is('super-admin')) {
                return route('super-admin.login');
            }
        }
        // return route('welcome');
        return redirect()->guest(route('login.line'));
    }
}
