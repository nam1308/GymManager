<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Laravel\Cashier\Subscription;

class PurchaseController extends Controller
{
    /**
     * @return \Illuminate\View\View
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function index(): View
    {
        $subscriptions = Subscription::orderBy('id', 'DESC')->paginate(30);
        return view('super-admin.purchase.index')->with([
            'subscriptions' => $subscriptions,
        ]);
    }
}
