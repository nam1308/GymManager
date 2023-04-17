<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\AdminActivateMail;
use App\Models\Admin;
use App\Models\Apply;
use App\Models\BasicSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class VendorController extends Controller
{
    public int $limit = 30;

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $vendors = Admin::where('role', 10)->orderBy('id', 'DESC')->paginate($this->limit, ['*']);
        return view('super-admin.channel.index')->with([
            'vendors' => $vendors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request): RedirectResponse
    {
        $apply_id = $request->apply_id;
        $status = $request->status;
        $role = $request->role;
        $login_id = create_login_id(8);

        try {
            // お申込みデータ取得
            $apply = Apply::find($apply_id);
            if (!$apply) {
                throw new Exception('申込データーが見つかりません');
            }
            if (!is_null($apply->status)) {
                throw new Exception('処理済です');
            }
            return DB::transaction(function () use ($apply, $status, $role, $login_id) {
                /////////////////////////////
                // 管理者登録
                $admin_user = new Admin();
                $admin_user->vendor_id = $apply->id;
                $admin_user->login_id = $login_id;
                $admin_user->name = $apply->name;
                $admin_user->email = $apply->email;
                $admin_user->role = $role;
                $admin_user->trainer_role = 20;
                $admin_user->password = $apply->password;
                $admin_user->save();
                /////////////////////////////
                // 登録
                $apply_model = Apply::find($apply->id);
                $apply_model->status = $status;
                $apply_model->deleted_at = Carbon::now();
                $apply_model->save();
                /////////////////////////////
                // ベーシック情報
                $basic_setting_model = new BasicSetting;
                $basic_setting_model->vendor_id = $apply->id;
                $basic_setting_model->company_name = $apply->company_name;
//                $basic_setting_model->store_name =
                $basic_setting_model->postal_code = $apply->postal_code;
                $basic_setting_model->prefecture_id = $apply->prefecture_id;
                $basic_setting_model->municipality = $apply->municipality;
                $basic_setting_model->business_hours = $apply->business_hours;
                $basic_setting_model->address_building_name = $apply->address_building_name;
                $basic_setting_model->phone_number = $apply->phone_number;
                $basic_setting_model->save();
                /////////////////////////////
                // メール送信
                Mail::to($admin_user->email)->send(new AdminActivateMail($admin_user));
                return redirect(route('super-admin.channel.show', $admin_user->vendor_id))->with('flash_message_success', '有効にしました');
            });
        } catch (\Throwable $e) {
            Log::error('#### Error 購入処理に失敗：' . $e->getMessage(), [
                'apply_id' => $apply_id,
            ]);
            return redirect(route('super-admin.apply.show', $apply_id))
                ->withInput()
                ->with('flash_message_danger', '処理に失敗しました。「' . $e->getMessage() . '」');
        }
    }

    /**
     * @param string $vendor_id
     * @return \Illuminate\View\View
     */
    public function show(string $vendor_id): View
    {
        $vendor = Admin::where('vendor_id', $vendor_id)->first();
        return view('super-admin.channel.show')->with([
            'channel' => $vendor,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
