<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::ADMIN_HOME;

    /**
     *
     * @var int
     */
    protected int $maxAttempts = 3;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showLoginForm(): View
    {
        $this->guard()->logout();
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login_id' => 'required|numeric',
            'password' => 'required'
        ]);

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        $admin = Admin::where('login_id', $data['login_id'])->first();
        if ($admin->block) {
            $this->incrementLoginAttempts($request);
            return back()->with('error_message', 'id was blocked');
        }
        if ($this->guard()->attempt(['login_id' => $data['login_id'], 'password' => $data['password']])) {
            return redirect()->route('admin.home');
        } else {
            $this->incrementLoginAttempts($request);
            return back()->with('error_message', 'ログインIDもしくはパスワードが間違っています');
        }
        $this->incrementLoginAttempts($request);
        return back();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->guard()->logout();
        return $this->loggedOut($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loggedOut(Request $request): RedirectResponse
    {
        return redirect(route('admin.login'));
    }

    // ログイン情報変更
    public function username(): string
    {
        return 'login_id';
    }
}
