<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * @param string $vendor_id
     * @param int $shop_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $shop_id): RedirectResponse
    {
        try {
            Shop::find($shop_id)->delete();
            return redirect(route('super-admin.basic-setting.show', $request->vendor_id))
                ->with('flash_message_success', '削除しました');
        } catch (\Throwable $e) {
            return back()
                ->with('flash_message_warning', $e->getMessage());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $shop_id
     * @return \Illuminate\View\View
     */
    public function show(Request $request, int $shop_id): View
    {
        $shop = Shop::find($shop_id);
        return view('super-admin.shop.show')->with([
            'shop' => $shop,
        ]);
    }
}
