<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LineLoginController extends Controller
{
//    private const LINE_OAUTH_URI = 'https://access.line.me/oauth2/v2.1/authorize?';
//    private const LINE_TOKEN_API_URI = 'https://api.line.me/oauth2/v2.1/';
//    private const LINE_PROFILE_API_URI = 'https://api.line.me/v2/';

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('user');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('line-login.index');
    }

    /**
     * ログイン
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function socialLogin(): RedirectResponse
    {
        return Socialite::driver('line')->redirect();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleProviderCallback(): RedirectResponse
    {
        $userSocial = Socialite::driver('line')->stateless()->user();
        $user = User::where('id', $userSocial->id)->first();
        if ($user) {
            // メアドがかぶってないかチェック
            $email_user = User::where('email', $userSocial->getEmail())->first();
            if (!$email_user) {
                // 登録
                User::where('id', $user->id)
                    ->update(['display_name' => $userSocial->getName(), 'email' => $userSocial->getEmail(), 'picture_url' => $userSocial->avatar]);
            } else {
                // 同じだったらそのままアップデート
                if ($email_user->id === $userSocial->id) {
                    User::where('id', $user->id)
                        ->update(['display_name' => $userSocial->getName(), 'email' => $userSocial->getEmail(), 'picture_url' => $userSocial->avatar]);
                } else {
                    User::where('id', $user->id)
                        ->update(['display_name' => $userSocial->getName(), 'picture_url' => $userSocial->avatar]);
                }
            }
            Auth::login($user);
        } else {
            // 新規登録
            $newUser = new User();
            $newUser->id = $userSocial->id;
            $newUser->display_name = $userSocial->getName();
            $newUser->email = $userSocial->getEmail();
            $newUser->picture_url = $userSocial->avatar;
            $newUser->save();
            //そのままログイン
            Auth::login($newUser);
        }
        if (session()->has('url.intended')) {
            return redirect(session()->get('url.intended'));
        }
        return redirect(route('home'));
    }
}
