<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLineApplyRequest;
use App\Http\Requests\UpdateLineApplyRequest;
use App\Models\BasicSetting;
use App\Models\LineMessage;
use App\Models\SuperAdmin;
use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use SendGrid;
use SendGrid\Mail\Mail;
use Throwable;

class LineApplyController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $admin = Auth::user();
        $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
        return view('admin.line-apply.index')->with([
            'line_message' => $line_message,
        ]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $admin = Auth::user();
        $basic_setting = BasicSetting::where('vendor_id', $admin->vendor_id)->first();
        $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
        return view('admin.line-apply.create')->with([
            'line_message' => $line_message,
            'basic_setting' => $basic_setting,
        ]);
    }

    /**
     * @param \App\Http\Requests\CreateLineApplyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateLineApplyRequest $request): RedirectResponse
    {
        // スーパー管理者一覧
        $super_user_emails = SuperAdmin::pluck('name', 'email')->toArray();
        DB::beginTransaction();
        $admin = Auth::user();
        try {
            $old_line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
            if ($old_line_message) {
                throw new Exception('既に登録済みです');
            }
            ///////////////////////////
            //コールバックURL作成
            $length = 16;
            function callback($length)
            {
                return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
            }

            $callback_message = callback($length);
            //チャンネルアイコン取得
            $channel_icon_img = $request->file('channel_icon');
            // 古いものは全部削除
            $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/line-icon/');
            $file = new Filesystem();
            $file->cleanDirectory($path);
            //画像保存処理
            $message_icon = $channel_icon_img->store('/public/assets/images/admin/' . $admin->vendor_id . '/line-icon');
            //画像ファイル名取得
            $message_icon_name = pathinfo($message_icon, PATHINFO_BASENAME);

            $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/line-icon/' . $message_icon_name);
            if (file_exists($path)) {
                // 加工
                $profile_photo = Image::make($path);
                $profile_photo->orientate();
                $profile_photo->fit(600, 600, function ($constraint) {
                    // 縦横比を保持したままにする
                    $constraint->aspectRatio();
                    // 小さい画像は大きくしない
                    $constraint->upsize();
                })->save(storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/line-icon/' . $message_icon_name));
            }
            $line_message = LineMessage::create([
                'vendor_id' => $admin->vendor_id,
                'status' => config('const.LINE_STATUS.APPLYING.STATUS'), //10:申請中 20:アクティベート待ち 30:アクティベート済み
                'callback' => $callback_message,
                'channel_name' => $request->channel_name,
                'channel_description' => $request->channel_description,
                'channel_icon' => $message_icon_name,
                'email' => $request->email,
                'privacy_policy_url' => $request->privacy_policy_url,
                'terms_of_use_url' => $request->terms_of_use_url,
                'store_url' => $request->store_url,
                'line_uri1' => $request->line_uri1,
            ]);
            $line_message->save();
            DB::commit();
            // Mail::to(config('tms_admin@example.com')->send(new LineApply($user));
            // Mail::to(config('app.mail_to'))->send(new LineApply($admin));
            /////////////////////////////
            // メール送信
            // 管理者全員にメールを送る
            $data = $admin;
            // $email = new Mail();
            // $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
            // $email->setSubject(getenv('APP_TITLE_JA') . "LINE利用申請のお知らせ");
            // $email->addTos($super_user_emails);
            // $email->addContent("text/html", strval(
            //     view('emails.line_apply_email', compact('data'))
            // ));
            // $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            // $sendgrid->send($email);

            //test email
            FacadesMail::send('emails.line_apply_email',['data' => $data],function ($m) use ($super_user_emails){
                $m->from('info@aporze.com','test');
                $m->to($super_user_emails)->subject('LINE利用申請のお知らせ');
            });
            return redirect(route('admin.line-apply'))->with('flash_message_success', '申請しました');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('flash_message_warning', '申請失敗 : ' . $e->getMessage())->withInput();
        }
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
     * @return \Illuminate\View\View
     */
    public function edit(): View
    {
        $admin = Auth::user();
        $message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
        return view('admin.line-apply.edit')->with([
            'message' => $message,
        ]);
    }

    /**
     * @param \App\Http\Requests\UpdateLineApplyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateLineApplyRequest $request): RedirectResponse
    {
        $message_icon_name = '';
        // スーパー管理者一覧
        $super_user_emails = SuperAdmin::pluck('name', 'email')->toArray();
        $admin = Auth::user();
        try {
            $old_line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
            if (!$old_line_message) {
                throw new Exception('データーが見つかりません');
            }

            //チャンネルアイコン取得
            $channel_icon_img = $request->file('channel_icon');
            if ($channel_icon_img) {
                // 古いものは全部削除
                $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/line-icon/');
                $file = new Filesystem();
                $file->cleanDirectory($path);
                // 画像保存処理
                $message_icon = $channel_icon_img->store('/public/assets/images/admin/' . $admin->vendor_id . '/line-icon');
                //画像ファイル名取得
                $message_icon_name = pathinfo($message_icon, PATHINFO_BASENAME);

                $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/line-icon/' . $message_icon_name);
                if (file_exists($path)) {
                    // 加工
                    $profile_photo = Image::make($path);
                    $profile_photo->orientate();
                    $profile_photo->fit(600, 600, function ($constraint) {
                        // 縦横比を保持したままにする
                        $constraint->aspectRatio();
                        // 小さい画像は大きくしない
                        $constraint->upsize();
                    })->save(storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/line-icon/' . $message_icon_name));
                }
            }

            $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
            $line_message->channel_name = $request->channel_name;
            $line_message->channel_description = $request->channel_description;
            $line_message->email = $request->email;
            $line_message->privacy_policy_url = $request->privacy_policy_url;
            $line_message->terms_of_use_url = $request->terms_of_use_url;
            $line_message->store_url = $request->store_url;
            $line_message->line_uri1 = $request->line_uri1;
            if ($message_icon_name) {
                $line_message->channel_icon = $message_icon_name;
            }
            $line_message->save();
            DB::commit();
            ////////////////////////
            /// システム管理者にメール
            $data = [
                'name' => $admin->name,
                'vendor_id' => $admin->vendor_id,
                'url' => url(route('super-admin.line-apply.show', $admin->vendor_id)),
            ];
            $email = new Mail();
            $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
            $email->setSubject(getenv('APP_TITLE_JA') . "LINEチャンネル変更のお知らせ");
            $email->addTos($super_user_emails);
            $email->addContent("text/html", strval(
                view('emails.line_apply_update', compact('data'))
            ));
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            $sendgrid->send($email);

            return redirect(route('admin.line-apply'))->with('flash_message_success', '修正依頼をしました');
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', '変更失敗 : ' . $e->getMessage())->withInput();
        }

    }
}
