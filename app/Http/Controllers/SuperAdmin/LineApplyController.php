<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLineMessageRequest;
use App\Models\Admin;
use App\Models\LineMessage;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Throwable;
use SendGrid;
use SendGrid\Mail\Mail;

class LineApplyController extends Controller
{
    public int $limit = 30;

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $query = LineMessage::query();
        if ($request->has('channel_name')) {
            $query->where('channel_name', 'like', '%' . $request->channel_name . '%');
        }
        if ($request->has('vendor_id')) {
            $query->where('vendor_id', 'like', '%' . $request->vendor_id . '%');
        }
        $line_messages = $query->orderBy('id', 'DESC')->paginate($this->limit, ['*']);
        return view('super-admin.line-apply.index')->with([
            'line_messages' => $line_messages,
            'channel_name' => $request->channel_name,
            'vendor_id' => $request->vendor_id,
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param string $vendor_id
     * @return \Illuminate\Contracts\View\View
     */
    public function show(string $vendor_id): View
    {
        // グローバルスコープを解除する
        $line_message_apply = LineMessage::where('vendor_id', $vendor_id)->first();
        return view('super-admin.line-apply.show')->with([
            'line_message_apply' => $line_message_apply,
        ]);
    }

    /**
     * @param string $vendor_id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit_line_message(string $vendor_id): View
    {
        $line_message = LineMessage::where('vendor_id', $vendor_id)->first();
        return view('super-admin.line-apply.edit-line-message')->with('line_message', $line_message);
    }

    /**
     * @param \App\Http\Requests\CreateLineMessageRequest $request
     * @param string $vendor_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function line_message_update(CreateLineMessageRequest $request, string $vendor_id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $vendor_id) {
                $qr_file_name = '';
                // 対象
                $admin = Admin::where('vendor_id', $vendor_id)->first();
                // パス
                $qr_code_img = $request->file('qr_code');
                if ($qr_code_img) {
                    // 古いものは全部削除
                    $path = storage_path('app/public/assets/images/admin/' . $admin->vendor_id . '/qr-code');
                    $file = new Filesystem();
                    $file->cleanDirectory($path);

                    // パス
                    $qr_code_save = $qr_code_img->store('/public/assets/images/admin/' . $admin->vendor_id . '/qr-code');
                    // 画像ファイル名取得
                    $qr_file_name = pathinfo($qr_code_save, PATHINFO_BASENAME);
                }
                // 保存
                $line_message_apply = LineMessage::where('vendor_id', $vendor_id)->first();
                $line_message_apply->channel_id = $request->channel_id;
                $line_message_apply->channel_secret = $request->channel_secret;
                $line_message_apply->channel_access_token = $request->channel_access_token;
                $line_message_apply->line_uri1 = $request->line_uri1;
                //$line_message_apply->status = config('const.LINE_STATUS.ENTERED.STATUS');
                if ($qr_file_name) {
                    $line_message_apply->qr_code = $qr_file_name;
                }
                $line_message_apply->save();
            });
            return redirect(route('super-admin.line-apply.show', $vendor_id))->with('flash_message_success', '修正しました');
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }

    /**
     * @param string $vendor_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function line_inform(string $vendor_id): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $admin = Admin::where('vendor_id', $vendor_id)->first();
            if (!$admin) {
                throw new Exception('店舗が見つかりません');
            }
            $line_message_apply = LineMessage::where('vendor_id', $vendor_id)->first();
            $line_message_apply->status = config('const.LINE_STATUS.ACCOMPLICE.STATUS');
            $line_message_apply->save();
            DB::commit();
//            Mail::to($admin->email)->send(new LineRegisterInform($line_message_apply));

            $data['url'] = url(route('channel.show', $admin->vendor_id));
            // $email = new Mail();
            // $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
            // $email->setSubject(getenv('APP_TITLE_JA') . "LINEチャンネル完了のお知らせ");
            // $email->addTo($admin->email);
            // $email->addContent("text/html", strval(
            //     view('emails.line_inform_email', compact('data')),
            // ));
            // $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            // $sendgrid->send($email);

            //test mail
            FacadesMail::send('emails.line_inform_email',['data'=>$data],function ($m) use ($admin){
                $m->from('info@aporze.com','test');
                $m->to($admin->email)->subject('LINEチャンネル完了のお知らせ');
            });

            return redirect(route('super-admin.line-apply.show', $vendor_id));
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }

    /**
     * テスト状態ステータス変更
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusChange(Request $request): RedirectResponse
    {
        $line_channel = LineMessage::where('vendor_id', $request->vendor_id)->first();
        $line_channel->status = $request->status;
        $line_channel->save();
        return redirect(route('super-admin.line-apply.show', $request->vendor_id))
            ->with('flash_message_success', 'ステータスを変更しました');
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
