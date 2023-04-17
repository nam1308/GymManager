<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;

class ProductController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $admin = Auth::guard('admin')->user();
        $customer = Cashier::findBillable($admin->stripe_id);
        $products = Product::$products;
        return view('admin.product.index', compact('customer'), compact('products'));
    }
}
