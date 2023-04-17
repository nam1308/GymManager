<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\AdminActivateMail;
use App\Models\Admin;
use App\Models\Apply;
use App\Models\BasicSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class AdminUserController extends Controller
{
//    public int $limit = 30;
//
//    /**
//     * @return \Illuminate\View\View
//     */
//    public function index(): View
//    {
//        $vendors = BasicSetting::orderBy('id', 'DESC')->paginate($this->limit, ['*']);
//        return view('super-admin.channel.index')->with([
//            'vendors' => $vendors
//        ]);
//    }
//
//    /**
//     * 作成画面
//     */
//    public function create()
//    {
//        //
//    }
//
//    /**
//     * 管理者作成処理
//     * @param \Illuminate\Http\Request $request
//     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
//     * @throws \Exception
//     */
//    public function store(Request $request)
//    {
//        // 申請番号
//        $apply_id = $request->apply_id;
//        // ステータス
//        $status = $request->status;
//        // 権限
//        $role = $request->role;
//        // ログインID作成
//        $login_id = create_login_id(8);
//        try {
//            // お申込みデータ取得
//            $apply = Apply::find($apply_id);
//            if (!$apply) {
//                throw new Exception('申込データーが見つかりません');
//            }
//            if (!is_null($apply->status)) {
//                throw new Exception('処理済です');
//            }
//            return DB::transaction(function () use ($apply, $status, $role, $login_id) {
//                /////////////////////////////
//                // 管理者登録
//                $admin_user = new Admin;
//                $admin_user->vendor_id = $apply->id;
//                $admin_user->login_id = $login_id;
//                $admin_user->name = $apply->name;
//                $admin_user->email = $apply->email;
//                $admin_user->role = $role;
//                $admin_user->trainer_role = 20;
//                $admin_user->password = $apply->password;
//                $admin_user->save();
//                /////////////////////////////
//                // 登録
//                $apply_model = Apply::find($apply->id);
//                $apply_model->status = $status;
//                $apply_model->deleted_at = Carbon::now();
//                $apply_model->save();
//                /////////////////////////////
//                // ベーシック情報
//                $basic_setting_model = new BasicSetting;
//                $basic_setting_model->vendor_id = $apply->id;
//                $basic_setting_model->company_name = $apply->company_name;
////                $basic_setting_model->store_name =
//                $basic_setting_model->postal_code = $apply->postal_code;
//                $basic_setting_model->prefecture_id = $apply->prefecture_id;
//                $basic_setting_model->municipality = $apply->municipality;
//                $basic_setting_model->business_hours = $apply->business_hours;
//                $basic_setting_model->address_building_name = $apply->address_building_name;
//                $basic_setting_model->phone_number = $apply->phone_number;
//                $basic_setting_model->save();
//
//                Mail::to($admin_user->email)->send(new AdminActivateMail($admin_user));
//                return redirect(route('super-admin.admin-user.show', $admin_user->vendor_id))->with('flash_message_success', '有効にしました');
//            });
//        } catch (Throwable $e) {
//            Log::error('#### Error 購入処理に失敗：' . $e->getMessage(), [
//                'apply_id' => $apply_id,
//            ]);
//            return redirect(route('super-admin.apply.show', $apply_id))
//                ->withInput()
//                ->with('flash_message_danger', '処理に失敗しました。「' . $e->getMessage() . '」');
//        }
//    }
//
//    /**
//     * @param $vendor_id
//     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
//     */
//    public function show($vendor_id)
//    {
//        // 基本情報取得
//        $channel = BasicSetting::where('vendor_id', $vendor_id)->first();
//
//        if (!$channel) {
//            return redirect(route('super-admin.admin-user'))
//                ->with('flash_message_warning', 'ベンダーが見つかりません');
//        }
//        // 管理者取得
//        $admin = Admin::where('vendor_id', $vendor_id)->where('role', 10)->first();
//
//        // トレーナ数取得
//        $trainers = Admin::where('vendor_id', $vendor_id)->get();
//        return view('super-admin.admin-user.show')
//            ->with([
//                'channel' => $channel,
//                'trainers' => $trainers,
//                'admin' => $admin,
//            ]);
//    }
//
//    public function user_index($vendor_id)
//    {
//        $users = Admin::where('vendor_id', $vendor_id)->orderBy('id', 'DESC')->paginate($this->limit, ['*']);
//        $channel = BasicSetting::where('vendor_id', $vendor_id)->first();
//
//        return view('super-admin.admin-user.user-index')
//            ->with([
//                'users' => $users,
//                'channel' => $channel,
//            ]);
//    }
//
//    /**
//     * @param $id
//     */
//    public function edit($id)
//    {
//        //
//    }
//
//    /**
//     * @param \Illuminate\Http\Request $request
//     * @param $id
//     */
//    public function update(Request $request, $id)
//    {
//        //
//    }
//
//    /**
//     * @param $id
//     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
//     */
//    public function destroy($id)
//    {
//        $admin_user = Admin::find($id);
//        if ($admin_user) {
//            $admin_user->delete();
//            return redirect(route('super-admin.admin-user'))
//                ->with('flash_message_success', $admin_user->name . 'さんを停止しました');
//        }
//        return redirect(route('super-admin.admin-user'))
//            ->with('flash_message_warning', $admin_user->name . 'さんがみつかりません');
//    }
}
