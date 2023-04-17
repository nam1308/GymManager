<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SuperProduct;
use App\Models\ProductCategory;
use Auth;
use DateTime;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $product_id
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $product_id): View
    {
        $product = Product::$products[$product_id];
        $admin = Auth::guard('admin')->user();
        $customer = Cashier::findBillable($admin->stripe_id);
        $intent = $admin->createSetupIntent();
        return view('admin.stripe.show', compact(
            'request',
            'customer',
            'product_id',
            'product',
            'intent'
        ));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $product_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request, string $product_id): RedirectResponse
    {
        $user = Auth::user();
        try {
            // またStripe顧客でなければ、新規顧客にする

            /**
             * [id] => cus_MNnke3XbDk3kGg
             * [object] => customer
             * [address] =>
             * [balance] => 0
             * [created] => 1662472130
             * [currency] =>
             * [default_currency] =>
             * [default_source] =>
             * [delinquent] =>
             * [description] =>
             * [discount] =>
             * [email] => bushi.chiba+22@gmail.com
             * [invoice_prefix] => 755347E0
             * [invoice_settings] => Stripe\StripeObject Object
             * (
             * [custom_fields] =>
             * [default_payment_method] =>
             * [footer] =>
             * [rendering_options] =>
             * )
             *
             * [livemode] =>
             * [metadata] => Stripe\StripeObject Object
             * (
             * )
             *
             * [name] => テスト１
             * [next_invoice_sequence] => 1
             * [phone] =>
             * [preferred_locales] => Array
             * (
             * )
             *
             * [shipping] =>
             * [tax_exempt] => none
             * [test_clock] =>
             */
            $product = Product::$products[$product_id];
            $coupon = $request->input('coupon');
            if ($coupon) {
                if ($coupon != $product['coupons'][$coupon]['id']) {
                    throw new Exception('クーポンコードが違います');
                }
            }
            // $stripeCustomer = $user->createOrGetStripeCustomer();
            // フォーム送信の情報から$paymentMethodを作成する
            // pm_1Lf9miJA7zcxKPztFvaTxCy4
            $payment_method = $request->input('stripePaymentMethod');
            // プランはconfigに設定したbasic_plan_idとする
            $plan = $product['id'];

            $payment_method = $request->input('stripePaymentMethod');
            $ldate = new DateTime();

            // 上記のプランと支払方法で、サブスクを新規作成する
            $product_category_id = new ProductCategory;

            SuperProduct::updateOrCreate(
                ['vendor_id' => $user->vendor_id],
                ['product_category_id' => $product_category_id->selectid($product_id)['id'], 'reference_date' => $ldate]
            );

            // 上記のプランと支払方法で、サブスクを新規作成する
            try {
                $user->newSubscription('default', $plan)->withCoupon($coupon)->create($payment_method);
                return redirect()->route('admin.purchase.succeeded', $product_id)->with('flash_message_success', '購入しました');
            }catch (IncompletePayment $e){
                throw $e;
                return redirect()->route('admin.purchase.failed', $product_id);
            }

            // 処理後に'ルート設定'にページ移行
        } catch (IncompletePayment $e) {
            return redirect()->route('admin.purchase.failed', $product_id);
        }
    }

    /**
     * @param string $product_id
     * @return \Illuminate\View\View
     */
    public function succeeded(string $product_id): View
    {
        return view('admin.stripe.succeeded');
    }

    /**
     * @param string $product_id
     * @return \Illuminate\View\View
     */
    public function failed(string $product_id): View
    {
        return view('admin.stripe.failed');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $product_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function change(Request $request, string $product_id): View|RedirectResponse
    {
        try {
            // 変更したいプラン
            $new_product = Product::$products[$product_id];
            // 管理者
            $admin = Auth::guard('admin')->user();
            $customer = Cashier::findBillable($admin->stripe_id);
            if ($request->isMethod('get')) {
                return view('admin.stripe.change')->with(compact(
                    'new_product',
                    'customer',
                    'product_id',
                ));
            }
            //
            $this->changeProcess($request, $product_id);
            return redirect(route('admin.purchase.change', $product_id))
                ->with('flash_message_success', 'プラン変更しました');
        } catch (Exception $e) {
            return redirect(route('admin.purchase.change', $product_id))
                ->with('flash_message_success', $e->getMessage());
        }
    }

    /**
     * @throws \Laravel\Cashier\Exceptions\SubscriptionUpdateFailure
     * @throws \Exception
     */
    private function changeProcess(Request $request, string $product_id): void
    {
        $admin = Auth::guard('admin')->user();
        $new_product = Product::$products[$product_id];
        $subscription_item = $admin->subscription('default')->items->first();
        // 現在のプラン
        $current_product = Product::$products[$subscription_item['stripe_price']];
        // 現在のトレーナ数
        $current_talent_count = $admin->where('vendor_id', $admin->vendor_id)->count();

        if ($current_talent_count > $new_product['trainer_count']) {
            throw new Exception('ダウングレードするにはトレーナを削除する必要があります。不要なトレーナを削除してからプラン変更をしてください。');
        }
        $customer = Cashier::findBillable($admin->stripe_id);
        $customer->subscription('default')->swap($product_id);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $product_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request, string $product_id): View|RedirectResponse
    {
        $product = Product::$products[$product_id];
        $admin = Auth::guard('admin')->user();
        $customer = Cashier::findBillable($admin->stripe_id);
        if ($customer->subscription('default')->ends_at) {
            return back();
        }
        if ($request->isMethod('get')) {
            return view('admin.stripe.cancel')->with([
                'intent' => $admin->createSetupIntent(),
                'customer' => $customer,
                'product' => $product,
                'product_id' => $product_id,
            ]);
        }
        $admin->subscription('default')->cancel();
        return redirect(route('admin.product.index'))->with('flash_message_success', '解約しました');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $product_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resume(Request $request, string $product_id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $customer = Cashier::findBillable($admin->stripe_id);
        $customer->subscription('default')->resume();
        return back()->with('flash_message_success', '継続しました');
    }
}
