<?php

namespace App\Http\Middleware\Admin;

use Auth;
use Closure;
use Illuminate\Http\Request;

class isPurchased
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin->subscribed('default')) {
            return redirect('admin/product')->with('flash_message_warning', 'プランを購入してください');
        }
        return $next($request);
    }
}
