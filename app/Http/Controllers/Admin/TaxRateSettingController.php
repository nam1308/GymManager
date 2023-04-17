<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRateSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class TaxRateSettingController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = Auth::user();
        $tax = TaxRateSetting::find($user->vendor_id);
        return view('admin.tax-rate-setting.index')->with(['tax' => $tax]);
    }

    /**
     * 更新
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        TaxRateSetting::updateOrCreate(['vendor_id' => $user->vendor_id], [
            'vendor_id' => $user->vendor_id,
            'tax' => $request->tax,
        ]);
        return redirect(route('admin.tax-rate-setting'))
            ->with('flash_message_success', '保存しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
