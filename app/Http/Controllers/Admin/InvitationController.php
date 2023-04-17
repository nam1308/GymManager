<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInvitationRequest;
use App\Models\Admin;
use App\Models\Invitation;
use App\Models\Product;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Illuminate\View\View;
use SendGrid;
use SendGrid\Mail\Mail;
use Throwable;

class InvitationController extends Controller
{

    protected $stripe;

    public function __construct(StripeController $stripe)
    {
        $this->stripe = $stripe;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $admin = Auth::user();
        $invitations = Invitation::where('vendor_id', $admin->vendor_id)->orderBy('id', 'DESC')->paginate(30, ['*']);
        return view('admin.invitation.index')->with(['invitations' => $invitations]);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $product = new Product();
        $current_plan = $product->currentPlan();
        $admin = Auth::guard('admin')->user();

        $trainers = Admin::where('vendor_id', $admin->vendor_id)->get();
        $invitations = Invitation::where('vendor_id', $admin->vendor_id)->get();
        // dd($admin->defaultPaymentMethod()->card);

        return view('admin.invitation.create')
            ->with(compact(
                'trainers',
                'current_plan',
                'invitations',
            ));
    }

    /**
     * @param \App\Http\Requests\CreateInvitationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateInvitationRequest $request): RedirectResponse
    {
        try {
            // 管理者
            $admin = Auth::guard('admin')->user();
            // プラン取得
            $subscription_item = $admin->subscription('default')->items->first();
            $current_product = Product::$products[$subscription_item->stripe_price];
            $trainer_count = Admin::where('vendor_id', $admin->vendor_id)->count();
            $invitation_count = Invitation::where('vendor_id', $admin->vendor_id)->count();

            if ($trainer_count + $invitation_count >= $current_product['trainer_count']) {
                // throw new Exception('招待できるトレーナ数が上限に達しました。トレーナ数を増やすにはプランをあげてください。');
                //update plan
                //get next plan
                try {
                    $products = Product::$products;
                    usort($products, function ($a, $b) {
                        return intval($a['price']) > intval($b['price']);
                    });
                } catch (\Exception $e) {
                    throw $e;
                }
                $next_index = array_keys($products, $current_product)[0] + 1;
                $next_product = $products[$next_index];
                //update datetime
                Product::where('vendor_id', $admin->vendor_id)->update(['reference_date' => Carbon::now()]);
                // update
                $this->stripe->change($request, $next_product['id']);
                //send mail
                FacadesMail::send('emails.change_plan_email', ['admin' => $admin], function ($m) use ($admin) {
                    $m->from('info@aporze.com');
                    $m->to($admin->email)->subject('アポルゼ！」サービスプラン変更のお知らせ');
                });

                return redirect(route('admin.invitation.create'))->with('flash_message_success', 'プランが変更されました。ご登録のメールをご確認ください');
            }

            // 既存会員登録防止
            $existing_user = Invitation::where(['vendor_id' => $admin->vendor_id, 'email' => $request->email])->first();
            if ($existing_user) {
                throw new Exception('既にこのメールアドレスは招待されました');
            }
            $invitation = new Invitation();
            $invitation->email = $request->email;
            $invitation->vendor_id = $admin->vendor_id;
            $invitation->token = str_random(64);
            $invitation->save();
            /////////////////////////////
            // メール送信
            $data = $invitation;
            // $email = new Mail();
            // $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
            // $email->setSubject(getenv('APP_TITLE_JA') . "トレーナーへの招待メール");
            // $email->addTo($request->email);
            // $email->addContent("text/html", strval(
            //     view('emails.trainer_invitation_template', compact('data'))
            // ));
            // $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            // $sendgrid->send($email);
            // Mail::to($request->email)->send(new TrainerInvitation($invitation));

            // test mail
            FacadesMail::send('emails.trainer_invitation_template', ['data' => $data], function ($m) use ($request) {
                $m->from('info@aporze.com', 'test');
                $m->to($request->email)->subject("トレーナーへの招待メール");
            });
            return redirect()->route('admin.invitation')->with('flash_message_success', __('招待しました。'));
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }

    /**
     * 再送信
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retransmission(Request $request): RedirectResponse
    {
        try {
            $invitation = Invitation::find($request->invitation_id)->first();
            if (!$invitation) {
                throw new Exception('招待データーが見つかりません');
            }
            if (Auth::user()->vendor_id != $invitation->vendor_id) {
                throw new Exception('間違った招待');
            }
            if (Auth::user()->role != config('const.ADMIN_ROLE.ADMIN.STATUS')) {
                throw new Exception('再送信する権限がありません');
            }
            /////////////////////////////
            // メール送信
            $data = $invitation;
            $email = new Mail();
            $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
            $email->setSubject(getenv('APP_TITLE_JA') . "トレーナーへの招待メール");
            $email->addTo($invitation->email);
            $email->addContent("text/html", strval(
                view('emails.trainer_invitation_template', compact('data'))
            ));
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            $sendgrid->send($email);
            return redirect()->route('admin.invitation')->with('flash_message_success', __('招待（再送信）しました。'));
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage());
        }
    }
}
