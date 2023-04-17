<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Requests\CreateAdminRequest;
use App\Models\AdminProfilePhoto;
use App\Models\Invitation;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use SendGrid\Mail\Mail;
use SendGrid;
use Throwable;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::ADMIN_HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest:admin');
    }

    /**
     * @param $token
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm($token): View
    {
        $invitation = Invitation::where('token', $token)->first();
        return view('admin.invitation.register')->with([
            'invitation' => $invitation,
        ]);
    }

//    public function showRegistrationForm()
//    {
//        return view('admin.auth.register');
//    }

    /**
     * @param \App\Http\Requests\CreateAdminRequest $request
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAdminRequest $request, string $token): RedirectResponse
    {
        // 招待されたトレーナーデータを作成
        try {
            $invitation = Invitation::where('token', $token)->first();
            if (!$invitation) {
                throw new Exception('トークンが見つかりません');
            }
            // 新しいトレーナー
            $new_admin = DB::transaction(function () use ($request, $invitation) {
                $login_id = create_login_id();
                // 管理者に登録
                $admin = new Admin;
                $admin->vendor_id = $invitation->vendor_id;
                $admin->login_id = $login_id;
                $admin->name = $request->name;
                $admin->self_introduction = $request->self_introduction;
                $admin->email = $invitation->email;
                $admin->password = Hash::make($request->password);
                $admin->role = 20;
                $admin->trainer_role = 20;
                $admin->save();
                // 招待を削除
                $invitation = Invitation::where('token', $invitation->token)->first();
                $invitation->delete();
                if ($request->profile_photo) {
                    // 古い写真があるか？
                    $admin_photo = AdminProfilePhoto::where(['vendor_id' => $invitation->vendor_id, 'admin_id' => $admin->id])->get();
                    // 削除
                    if ($admin_photo) {
                        foreach ($admin_photo as $photo) {
                            $photo->delete();
                            $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id . '/' . $photo->file);
                            if (file_exists($path)) {
                                $delete_file_path = 'public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id . '/' . $photo->file;
                                Storage::delete($delete_file_path);
                            }
                        }
                    }
                    //画像保存先作成
                    $photoData = $request->profile_photo->store('public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id);
                    //画像ファイル名取得
                    $name = pathinfo($photoData, PATHINFO_BASENAME);
                    //データベース保存
                    AdminProfilePhoto::create([
                        'vendor_id' => $admin->vendor_id,
                        'admin_id' => $admin->id,
                        'file' => $name,
                    ])->save();
                    // パス
                    $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id . '/' . $name);
                    if (file_exists($path)) {
                        // 加工
                        $profile_photo = Image::make($path);
                        $profile_photo->orientate();
                        $profile_photo->fit(600, 600, function ($constraint) {
                            // 縦横比を保持したままにする
                            $constraint->aspectRatio();
                            // 小さい画像は大きくしない
                            $constraint->upsize();
                        })->save(storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id . '/' . $name));
                    }
//                    //画像保存先作成
//                    $photoData = $request->profile_photo->store('public/assets/images/admin/' . $admin->vendor_id . '/profile/' . $admin->id);
//                    //画像ファイル名取得
//                    $name = pathinfo($photoData, PATHINFO_BASENAME);
//                    //データベース保存
//                    AdminProfilePhoto::create([
//                        'vendor_id' => $admin->vendor_id,
//                        'admin_id' => $admin->id,
//                        'file' => $name
//                    ])->save();
                }
                return $admin;
            });
            /////////////////////////////
            // 新しいトレーナーにメールを送る
//             $email = new Mail();
//             $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
//             $email->setSubject(getenv('APP_TITLE_JA') . "ログインIDのお知らせ");
//             $email->addTo($new_admin->email);
//             $email->addContent("text/html", strval(
// //                view('emails.trainer_activate', compact('data'))
//                 view('emails.trainer_register_email', compact('new_admin'))
//             ));
//             $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
//             $sendgrid->send($email);
            // 管理者にメールを送る
            $admin = Admin::where('vendor_id', $invitation->vendor_id)->where('role', config('const.ADMIN_ROLE.ADMIN.STATUS'))->first();
//             $email = new Mail();
//             $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
//             $email->setSubject(getenv('APP_TITLE_JA') . "ログインIDのお知らせ");
//             $email->addTo($admin->email);
//             $email->addContent("text/html", strval(
// //                view('emails.trainer_activate', compact('data'))
//                 view('emails.trainer_activate', compact('new_admin'))
//             ));
//             $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
//             $sendgrid->send($email);
            // メール送信
//            Mail::to($invitation->email)->send(new TrainerRegisterMail($new_admin));
            //test mail
            FacadesMail::send('emails.trainer_register_email',['new_admin'=>$new_admin],function ($m) use ($new_admin){
                $m->from('info@aporze.com','test');
                $m->to($new_admin->email)->subject("ログインIDのお知らせ");
            });
            FacadesMail::send('emails.trainer_activate',['new_admin'=>$new_admin],function ($m) use ($admin){
                $m->from('info@aporze.com','test');
                $m->to($admin->email)->subject('ログインIDのお知らせ');
            });
            return redirect(route('admin.register.thanks', $token));
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }

    public function thanks()
    {
        return view('admin.invitation.thanks');
    }

    protected function guard()
    {
        // return Auth::guard('admin');
    }

    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'mx:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
