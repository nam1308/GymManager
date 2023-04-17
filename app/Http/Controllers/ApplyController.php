<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateApplyRequest;
use App\Models\Admin;
use App\Models\Apply;
use App\Models\BasicSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use SendGrid;
use SendGrid\Mail\Mail;
use App\Models\SuperProduct;

class ApplyController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('apply.index');
    }

    /**
     * @param \App\Http\Requests\CreateApplyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function store(CreateApplyRequest $request): RedirectResponse
    {
        Log::debug('きた');
        $login_id = create_login_id(8);

        try {
            DB::transaction(function () use ($request, $login_id) {
                $vendor_id = Str::random(20);
                ///////////////////////////
                /// 申込み
                $apply = new Apply;
                $apply->id = $vendor_id;
                $apply->company_name = $request->company_name;
                $apply->postal_code = $request->postal_code;
                $apply->prefecture_id = $request->prefecture_id;
                $apply->municipality = $request->municipality;
                $apply->address_building_name = $request->address_building_name;
                $apply->phone_number = $request->phone_number;
                $apply->name = $request->name;
                $apply->email = $request->email;
                $apply->password = bcrypt($request->password);
                $apply->status = 1;
                $apply->deleted_at = Carbon::now();
                $apply->save();
                ///////////////////////////
                /// 申込み
                $admin_user = new Admin();
                $admin_user->vendor_id = $vendor_id;
                $admin_user->login_id = $login_id;
                $admin_user->name = $apply->name;
                $admin_user->email = $apply->email;
                $admin_user->role = 10;
                // 非表示に設定
                $admin_user->trainer_role = 10;
                $admin_user->password = $apply->password;
                $admin_user->save();
                /////////////////////////////
                // ベーシック情報
                $basic_setting_model = new BasicSetting;
                $basic_setting_model->vendor_id = $vendor_id;
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
                /////////////////////////////
                // テーブル データ プロダクトを作成する
                SuperProduct::updateOrCreate(['vendor_id' => $vendor_id]);

                // $email = new Mail();
                // $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
                // $email->setSubject(getenv('APP_TITLE_JA') . "アカウントが有効になりました");
                // $email->addTo($apply->email, $apply->name);
                // $email->addContent("text/html", strval(
                //     view('emails.admin_activate_email', compact('data'))
                // ));
                // $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
                // $sendgrid->send($email);

                //test mail
                FacadesMail::send('emails.admin_activate_email',['data'=>$data],function ($m) use ($apply){
                    $m->from('info@aporze.com','test');
                    $m->to($apply->email,$apply->name)->subject("アカウントが有効になりました");
                });
            });
//            // 送信
//            $data = $request->all();
//            // お申込者に
//            $email = new Mail();
//            $email->setFrom("info@aporze.com", getenv('APP_TITLE_JA'));
//            $email->setSubject(getenv('APP_TITLE_JA') . "お申込みありがとうございます");
//            $email->addTo($request->email, $request->name);
//            $email->addContent("text/html", strval(
//                view('emails.apply_to', compact('data'))
//            ));
//            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
//            $response = $sendgrid->send($email);

//            // 運営に
//            $super_user_emails = SuperAdmin::pluck('name', 'email')->toArray();
//            $email = new Mail();
//            $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
//            $email->setSubject(getenv('APP_TITLE_JA') . "新規お申込み");
//            $email->addTos($super_user_emails, 'お申込み');
//            $email->addContent("text/html", strval(
//                view('emails.apply_from', compact('data'))
//            ));
//            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
//            $sendgrid->send($email);
//            $response = $sendgrid->send($email);



            return redirect(route('apply.thanks'));
        } catch (Exception $e) {
//            print_r($e->getMessage());
//            exit;
//            Log::debug($e->getMessage());
            return back()->with('flash_message_warning', $e->getMessage())->withInput();
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function thanks(): View
    {
        return view('apply.thanks');
    }
}
