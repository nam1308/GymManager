<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateApplyRequest;
use App\Models\Admin;
use App\Models\Apply;
use App\Models\BasicSetting;
use App\Models\Course;
use App\Models\Product;
use App\Models\Shop;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BasicSettingController extends Controller
{
    public int $limit = 30;

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $vendors = Admin::where('role', 10)->with('basicSetting')->with('apply')->with('product')->orderBy('id', 'DESC')->paginate($this->limit, ['*']);
        return view('super-admin.basic-setting.index')->with([
            'vendors' => $vendors,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        return view('super-admin.basic-setting.create');
    }

    /**
     * @param \App\Http\Requests\CreateApplyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateApplyRequest $request): RedirectResponse
    {
        try {
            // 保存
            $apply = new Apply;
            $apply->id = Str::random(20);
            $apply->company_name = $request->company_name;
            $apply->postal_code = $request->postal_code;
            $apply->prefecture_id = $request->prefecture_id;
            $apply->municipality = $request->municipality;
            $apply->address_building_name = $request->address_building_name;
            $apply->phone_number = $request->phone_number;
            $apply->name = $request->name;
            $apply->email = $request->email;
            $apply->password = bcrypt($request->password);
            $apply->save();
            return redirect(route('super-admin.apply'))->with('flash_message_success', '店舗を登録しました。有効にしてください。');
        } catch (Exception $e) {
            Log::debug($e->getMessage());
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }

    /**
     * @param string $vendor_id
     * @return \Illuminate\View\View
     */
    public function show(string $vendor_id): View
    {
        $basic_setting = BasicSetting::where('vendor_id', $vendor_id)->first();
        $trainers = Admin::where('vendor_id', $vendor_id)->get();
        $shops = Shop::where('vendor_id', $vendor_id)->get();
        $courses = Course::where('vendor_id', $vendor_id)->get();
        $product = Product::where('vendor_id', $vendor_id)->first();
        $admin = Admin::where('vendor_id', $vendor_id)->where('role', config('const.ADMIN_ROLE.ADMIN'))->first();
        return view('super-admin.basic-setting.show')->with([
            'trainers' => $trainers,
            'shops' => $shops,
            'courses' => $courses,
            'basic_setting' => $basic_setting,
            'product' => $product,
            'admin' => $admin
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(string $vendor_id)
    {
        $basic_setting = BasicSetting::where('vendor_id', $vendor_id)->firstOrFail();
        $isBlock = Admin::where('vendor_id', $vendor_id)->where('role', config('const.ADMIN_ROLE.ADMIN'))->first()->block;
        return view('super-admin.basic-setting.edit', ['data' => $basic_setting, 'isBlock' => $isBlock]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $vendor_id)
    {
        $data = $request->validate([
            'company_name' => 'required',
            'postal_code' => 'required|numeric',
            'prefecture_id' => 'required|numeric',
            'municipality' => 'required',
            'phone_number' => 'required',
            'address_building_name' => 'nullable',
            'note_super_admin' => 'nullable',
            'block' => 'required|boolean'
        ]);

        BasicSetting::where('vendor_id', $vendor_id)->update(array_filter($data, function ($k) {
            return $k != 'block';
        }, ARRAY_FILTER_USE_KEY));

        Admin::where('vendor_id', $vendor_id)->update(array_filter($data, function ($k) {
            return $k == 'block';
        }, ARRAY_FILTER_USE_KEY));

        return redirect()->route('super-admin.basic-setting')->with('flash_message', '成功');
    }

    public function updateBlock(Request $request, $vendor_id)
    {
        try {
            $data = $request->validate([
                'block' => 'required|boolean'
            ]);

            Admin::where('vendor_id', $vendor_id)->update($data);
            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
