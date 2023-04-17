<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Mockery\Exception;
use Throwable;

class ShopController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();
        $shops = Shop::where('vendor_id', $admin->vendor_id)->orderBy('id', 'DESC')->limit(30)->get();
        return view('admin.shop.index')->with(['shops' => $shops]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.shop.create');
    }

    /**
     * @param \App\Http\Requests\CreateShopRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateShopRequest $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $shop = new Shop();
        $shop->vendor_id = $admin->vendor_id;
        $shop->name = $request->name;
        $shop->url = $request->url;
        $shop->phone_number = $request->phone_number;
        $shop->postal_code = $request->postal_code;
        $shop->prefecture_id = $request->prefecture_id;
        $shop->municipality = $request->municipality;
        $shop->address_building_name = $request->address_building_name;
        $shop->contents = $request->contents;
        $shop->save();
        return redirect(route('admin.shop'))
            ->with('flash_message_success', '登録しました');
    }

    /**
     * @param $shop_id
     * @return \Illuminate\View\View
     */
    public function edit($shop_id): View
    {
        $admin = Auth::guard('admin')->user();
        $shop = Shop::where(['vendor_id' => $admin->vendor_id, 'id' => $shop_id])->first();
        return view('admin.shop.edit')->with(['shop' => $shop]);
    }

    /**
     * @param \App\Http\Requests\UpdateShopRequest $request
     * @param $shop_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateShopRequest $request, $shop_id): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();
        $shop = Shop::where(['vendor_id' => $admin->vendor_id, 'id' => $shop_id])->first();
        try {
            if (!$shop) {
                throw new Exception('店舗が見つかりません');
            }
            DB::transaction(function () use ($request, $shop_id) {
                $shop = Shop::find($shop_id);
                $shop->name = $request->name;
                $shop->url = $request->url;
                $shop->phone_number = $request->phone_number;
                $shop->postal_code = $request->postal_code;
                $shop->prefecture_id = $request->prefecture_id;
                $shop->municipality = $request->municipality;
                $shop->address_building_name = $request->address_building_name;
                $shop->contents = $request->contents;
                $shop->save();
                return $shop;
            });
            return redirect(route('admin.shop'))
                ->with('flash_message_success', '保存しました');
        } catch (Throwable $e) {
            return redirect(route('admin.shop.edit', $shop_id))
                ->with('flash_message_danger', '保存失敗');
        }
    }

    /**
     * @param $shop_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($shop_id): RedirectResponse
    {
        print_r($shop_id);
        exit;
        $admin = Auth::guard('admin')->user();
        $shop = Shop::where(['vendor_id' => $admin->vendor_id, 'id' => $shop_id])->first();
        if ($shop) {
            $shop->delete();
            return redirect(route('admin.shop'))
                ->with('flash_message_success', '削除しました');
        } else {
            return redirect(route('admin.shop'))
                ->with('flash_message_danger', '削除失敗');
        }
    }
}
