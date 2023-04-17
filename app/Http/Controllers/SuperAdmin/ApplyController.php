<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Apply;
use App\Models\BasicSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\View\View;
use SendGrid;
use SendGrid\Mail\Mail;
use Throwable;

class ApplyController extends Controller
{
    private int $limit = 30;

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $applies = Apply::orderBy('id', 'DESC')->paginate($this->limit, ['*']);
        return view('super-admin.apply.index')->with(['applies' => $applies]);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function show($id): View
    {
        $apply = Apply::find($id);
        return view('super-admin.apply.show')->with([
            'apply' => $apply
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id): View
    {
        $apply = Apply::find($id);
        return view('super-admin.apply.edit')->with([
            'apply' => $apply
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $apply_id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, string $apply_id): RedirectResponse
    {
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
                $data = $admin_user;
                // $email = new Mail();
                // $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
                // $email->setSubject(getenv('APP_TITLE_JA') . "アカウントが有効になりました");
                // $email->addTo($apply->email, $apply->name);
                // $email->addContent("text/html", strval(
                //     view('emails.admin_activate_email', compact('data'))
                // ));
                // $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
                // $sendgrid->send($email);
//                Mail::to($admin_user->email)->send(new AdminActivateMail($admin_user));
                FacadesMail::send('emails.admin_activate_email',['data'=>$data],function ($m) use ($apply){
                    $m->from('info@aporze.com','test');
                    $m->to($apply->email)->subject('アカウントが有効になりました');
                });
                return redirect(route('super-admin.basic-setting.show', $admin_user->vendor_id))->with('flash_message_success', '有効にしました');
            });
        } catch (Throwable $e) {
            Log::error('#### Error 処理に失敗：' . $e->getMessage(), [
                'apply_id' => $apply_id,
            ]);
            return redirect(route('super-admin.apply.show', $apply_id))
                ->withInput()
                ->with('flash_message_danger', '処理に失敗「' . $e->getMessage() . '」');
        }
//        print_r($id);exit;
//        try {
//            $apply = Apply::find($id);
//            $apply->name = $request->name;
//            $apply->phone_number = $request->phone_number;
//            $apply->postal_code = $request->postal_code;
//            $apply->prefecture_id = $request->prefecture_id;
//            $apply->municipality = $request->municipality;
//            $apply->address_building_name = $request->address_building_name;
//            $apply->email = $request->email;
//            $apply->company_name = $request->company_name;
//            $apply->save();
//            return redirect(route('super-admin.apply.show', $id))
//                ->with('flash_message_success', '保存しました');
//        } catch (Throwable $e) {
//            return back()->with('flash_message_warning', '既にこのメールアドレスは登録されています。');;
//        }
    }

    /**
     * @param $apply_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($apply_id): RedirectResponse
    {
        $apply = Apply::find($apply_id);
        if ($apply) {
            $apply->delete();
            return redirect(route('super-admin.apply'))->with('flash_message_success', $apply->name . 'さんを却下しました');
        }
        return redirect(route('super-admin.apply'))->with('flash_message_success', '却下失敗しました');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function active($id): RedirectResponse
    {
        $apply = Apply::onlyTrashed()->when('id', $id)->get();
        if ($apply) {
            apply::onlyTrashed()->when('id', $id)->restore();
            return redirect(route('admin.user'))
                ->with('flash_message_success', $apply->name . 'さんをリストアしました');
        }
        return redirect(route('admin.user'))
            ->with('flash_message_danger', $apply->name . 'さんをリストア失敗しました');
    }
}
