<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBasicSettingRequest;
use App\Models\BasicSetting;
use App\Models\BusinessHours;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use SplFileObject;
use Throwable;

class BasicSettingController extends Controller
{
    /**
     * BasicSettingsController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $admin = Auth::user();
        $basic_setting = BasicSetting::where('vendor_id', $admin->vendor_id)->first();
        $business_hour = BusinessHours::selectRaw('DATE_FORMAT(start, "%H:%i") AS start')
            ->selectRaw('DATE_FORMAT(end, "%H:%i") AS end')
            ->where('vendor_id', $admin->vendor_id)->first();
        return view('admin.basic-setting.index')->with([
            'admin' => $admin,
            'basic_setting' => $basic_setting,
            'business_hour' => $business_hour
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $file_path = $request->file('csv')->path();
        // CSV取得
        $file = new SplFileObject($file_path);
        $file->setFlags(
            SplFileObject::READ_CSV | // CSVとして行を読み込み
            SplFileObject::READ_AHEAD | // 先読み／巻き戻しで読み込み
            SplFileObject::SKIP_EMPTY | // 空行を読み飛ばす
            SplFileObject::DROP_NEW_LINE // 行末の改行を読み飛ばす
        );
        CarColor::truncate();
        $car_color = new CarColor();
        foreach ($file as $line) {
            $car_color->create([
                'color' => $line[0],
            ]);
        }

        return redirect(route('admin.car.color'))
            ->with('flash_message_success', 'インポートしました');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(): View
    {
        $admin = Auth::user();
        $basic_setting = BasicSetting::where('vendor_id', $admin->vendor_id)->first();
        return view('admin.basic-setting.edit')->with(['basic_setting' => $basic_setting]);
    }

    /**
     * 更新
     *
     * @param UpdateBasicSettingRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function update(UpdateBasicSettingRequest $request)
    {
        try {
            $admin = Auth::user();
            $basic_setting = BasicSetting::where('vendor_id', $admin->vendor_id)->first();
            $basic_setting->company_name = $request->company_name;
            $basic_setting->postal_code = $request->postal_code;
            $basic_setting->prefecture_id = $request->prefecture_id;
            $basic_setting->municipality = $request->municipality;
            $basic_setting->phone_number = $request->phone_number;
            $basic_setting->address_building_name = $request->address_building_name;
            $basic_setting->business_hours = $request->business_hours;
            $basic_setting->other_memo = $request->other_memo;
            $basic_setting->save();
            return redirect(route('admin.basic-setting'))->with('flash_message_success', '保存しました');
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage());
        }
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
