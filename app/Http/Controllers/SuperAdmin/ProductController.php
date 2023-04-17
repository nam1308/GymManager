<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public $limit = 30;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * 一覧
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $product_code = $request->product_code;
        $name = $request->name;
        $client_ip = $request->client_ip;
        $search = $request->search;
        $query = Product::query();

        // 検索だったら
        if ($search) {
            if ($request->product_code) {
                $query->where('code', $product_code);
            }
            if ($request->name) {
                $query->where('name', 'like', '%' . $name . '%');
            }
        }
        // 普通
        $products = $query->orderBy('id', 'DESC')->paginate($this->limit, ['*']);
        return view('super-admin.product.index')->with([
            'products' => $products,
            'product_code' => $product_code,
            'name' => $name,
            'client_ip' => $client_ip,
        ]);
    }

    /**
     * 作成
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        // $product_categories = config('const.PRODUCT_CATEGORIES');
        $product_categories = ProductCategory::all();
        return view('super-admin.product.create')->with('product_categories', $product_categories);
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $new_product_code = Product::createCode($request->product_category_id);
        $product = new Product();
        $product->vendor_id = Auth::user()->vendor_id;
        $product->name = $request->name;
        $product->code = $new_product_code;
        $product->client_ip = $request->client_ip;
        $product->price = str_replace(',', '', $request->price);
        $product->purchase_count = $request->purchase_count;
        $product->free_term = $request->free_term;
        $product->product_category_id = $request->product_category_id;
        $product->save();
        return redirect(route('admin.product'))
            ->with('flash_message_success', '保存しました');
    }

    /**
     *
     * 製品をコピーする
     *
     * @param $product_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function copy($product_id)
    {
        // オリジナルを取得
        $product_org = Product::find($product_id);
        if (!$product_org) {
            return redirect()
                ->route('admin.product')
                ->with('flash_message_warning', '商品がみつかりませんでした');
        }
        $product = new Product();
        $product->vendor_id = $product_org->vendor_id;
        $product->code = date('His');
        $product->name = $product_org->name;
        $product->client_ip = $product_org->client_ip;
        $product->price = $product_org->price;
        $product->purchase_count = $product_org->purchase_count;
        $product->product_category_id = $product_org->product_category_id;
        $product->free_term = $product_org->free_term;
        $product->save();
        return redirect(route('admin.product.edit', $product->id))
            ->with('flash_message_success', 'コピーしました。必要な箇所を修正してください。');
    }

    /**
     * 編集
     *
     * @param $product_id
     * @return Application|Factory|View|RedirectResponse
     */
    public function edit($product_id)
    {
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return redirect()
                ->route('admin.product')
                ->with('flash_message_warning', '商品がみつかりませんでした');
        }
        return view('super-admin.product.edit')
            ->with('product', $product);
    }

    /**
     * @param $product_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($product_id)
    {
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return redirect()
                ->route('admin.product')
                ->with('flash_message_warning', '商品がみつかりませんでした');
        }
        return view('super-admin.product.show')
            ->with('product', $product);
    }

    /**
     * 更新
     * @param \App\Http\Requests\UpdateProductRequest $request
     * @param $product_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateProductRequest $request, $product_id)
    {
        $product = Product::where('id', $product_id)->first();
        if (!$product) {
            return redirect()
                ->route('admin.product')
                ->with('flash_message_warning', '商品がみつかりませんでした');
        }
        $product->name = $request->name;
        $product->client_ip = $request->client_ip;
        $product->price = $request->price;
        $product->purchase_count = $request->purchase_count;
        $product->free_term = $request->free_term;
        $product->save();
        return redirect(route('admin.product.show', $product_id))
            ->with('flash_message_success', '保存しました');
    }

    /**
     * 削除
     *
     * @param $product_code
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy($product_code)
    {
        $product = Product::find($product_code);
        if (!$product) {
            return redirect()
                ->route('admin.product')
                ->with('flash_message_warning', '商品がみつかりませんでした');
        }
        $product->delete();
        return redirect(route('admin.product'))
            ->with('flash_message_success', $product->name . 'を停止しました');
    }
}
