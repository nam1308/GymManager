<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Contracts\View\View;
use Laravel\Cashier\Cashier;

class PayController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $admin = Auth::guard('admin')->user();
        $accountInfor = paymentData();

        return view('admin.pay.index', compact('admin', 'accountInfor'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $admin = Auth::guard('admin')->user();
        $intent = $admin->createSetupIntent();
        return view('admin.pay.create', compact('intent'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $payment_method = $request->input('stripePaymentMethod');
        $products = Product::$products;
        usort($products, function ($a, $b) {
            return intval($a['trainer_count']) > intval($b['trainer_count']);
        });
        $user->newSubscription('default', $products[0]['id'])->create($payment_method);
        return redirect(route('admin.pay'))->with('flash_message_success', 'サインアップ成功');
    }


    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, string $id): View
    {
        $admin = Auth::guard('admin')->user();
        $intent = $admin->createSetupIntent();
        $accountInfor = DataPay($id);
        return view('admin.pay.edit', compact('intent','accountInfor'));
    }

    /**
     * @param $id
     */
    public function update(Request $request, $id){
        $user = Auth::user();
        $payment_method = $request->input('stripePaymentMethod');
        $user->updateDefaultPaymentMethod($payment_method);
        return redirect(route('admin.pay'))->with('flash_message_success', 'サインアップ成功');
    }

}
