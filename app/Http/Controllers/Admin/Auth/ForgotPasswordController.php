<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PasswordResets;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Mockery\Generator\StringManipulation\Pass\Pass;
use SendGrid;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;
use TheSeer\Tokenizer\Exception;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm(): View
    {
        return view('admin.auth.passwords.email');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        try {
            $this->validateEmail($request);
            $admin = Admin::where('email', $request->email)->first();
            if (!$admin) {
                throw new Exception('メールアドレスがみつかりません');
            }
            $token = hash('sha256', Str::random(60));

            $email = PasswordResets::where('email', $request->email)->first();
            if (!$email) {
                $password_reset = new PasswordResets();
                $password_reset->email = $request->email;
                $password_reset->token = $token;
                $password_reset->created_at = Carbon::now();
                $password_reset->save();
            } else {
                $email->token = $token;
                $email->created_at = Carbon::now();
                $email->save();
            }

            $data['token'] = $token;
            $data['email'] = $request->email;
            $email = new Mail();
            $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
            $email->setSubject(getenv('APP_TITLE_JA') . 'パスワードリセット');
            $email->addTo($admin->email);
            $email->addContent("text/html", strval(
                view('emails.password_reset', compact('data'))
            ));
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            $sendgrid->send($email);
            return back()->with('status', 'パスワードリセットメールを送信しました。');
        } catch (Exception | TypeException $e) {
            return back()->with('status', $e->getMessage());
        }
    }

    /**
     * Validate the email for the given request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $password_reset = PasswordResets::where('token', $request->token)->where('email', $request->email)->first();
            if (!$password_reset) {
                throw new Exception('パスワードリセットデーターがみつかりません');
            }
            $target_day = Carbon::create($password_reset->created_at);
            $day = Carbon::now();
            $subHour = $day->copy()->subHour();
            if (!$target_day->between($day, $subHour)) {
                throw new Exception('有効期限が切れました。');
            }
            $admin = Admin::where('email', $request->email)->first();
            $admin->password = Hash::make($request->password);

            $password = PasswordResets::where('email', $request->email)->first();
            $password->delete();
            $admin->save();
            return back()->with('status', "パスワードを変更しました。" . "<a href=" . route('admin.login') . ">ログイン画面</a>");
        } catch (Exception $e) {
            return back()->with('status', $e->getMessage());
        }
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker(): PasswordBroker
    {
        return Password::broker();
    }

    /**
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, string $token): View
    {
        $password_resets = PasswordResets::where('token', $token)->first();
        return view('admin.auth.passwords.reset')->with([
            'password_resets' => $password_resets,
            'email' => $request->query('email'),
            'token' => $token,
        ]);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request): array
    {
        return $request->only('email');
    }
}
