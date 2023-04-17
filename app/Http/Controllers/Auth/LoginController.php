<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function showLoginForm(): RedirectResponse
    {
        return redirect('/');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login.line');
    }
}

//
//namespace App\Http\Controllers\Auth;
//
//use App\Http\Controllers\Controller;
//use App\Models\LineLogin;
//use App\Models\User;
//use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Cookie;
//use Illuminate\Support\Str;
//use Symfony\Component\HttpFoundation\RedirectResponse;
//use Throwable;
//
//class LoginController extends Controller
//{
//    private const LINE_OAUTH_URI = 'https://access.line.me/oauth2/v2.1/authorize?';
//    private const LINE_TOKEN_API_URI = 'https://api.line.me/oauth2/v2.1/';
//    private const LINE_PROFILE_API_URI = 'https://api.line.me/v2/';
//    /*
//    |--------------------------------------------------------------------------
//    | Login Controller
//    |--------------------------------------------------------------------------
//    |
//    | This controller handles authenticating users for the application and
//    | redirecting them to your home screen. The controller uses a trait
//    | to conveniently provide its functionality to your applications.
//    |
//    */
//
//    use AuthenticatesUsers;
//
//    /**
//     * Where to redirect users after login.
//     *
//     * @var string
//     */
//    protected string $redirectTo = RouteServiceProvider::HOME;
//
//    /**
//     * Create a new controller instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        $this->middleware('guest')->except('logout');
//    }
//
//    /**
//     * @param string $callback_id
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     */
//    public function socialLogin(string $callback_id): RedirectResponse
//    {
//        try {
//            $line_login = LineLogin::where('callback', $callback_id)->first();
//            if (!$line_login) {
//                throw new \Exception('データーが見つかりません');
//            }
//            // state生成
//            $state = Str::random(40);
//            Cookie::queue('state', $state, 100);
//            // nonce生成
//            $nonce = Str::random(40);
//            Cookie::queue('nonce', $nonce, 100);
//            // LINE認証
//            $uri = self::LINE_OAUTH_URI . "response_type=code";
//            $client_id_uri = "&client_id=" . $line_login->channel_id;
//            $redirect_uri = "&redirect_uri=" . url(route('login.line.callback', $line_login->callback));
//            $state_uri = "&state=" . $state;
//            $scope_uri = "&scope=profile%20openid%20email";
//            $prompt_uri = "&prompt=consent";
//            $nonce_uri = "&nonce=";
//            return redirect($uri . $client_id_uri . $redirect_uri . $state_uri . $scope_uri . $prompt_uri . $nonce_uri);
//        } catch (Throwable $e) {
//            print_r($e->getMessage());
//            exit;
//        }
//        //return Socialite::driver('line')->redirect();
//    }
//
//    /**
//     * @param \Illuminate\Http\Request $request
//     * @param string $callback_id
//     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
//     */
//    public function handleProviderCallback(Request $request, string $callback_id)
//    {
//        try {
//            $code = $request->query('code');
//            $line_login = LineLogin::where('callback', $callback_id)->first();
//            // トークン取得
//            $access_token_info = $this->fetchTokenInfo($code, $callback_id);
//            // プロフィール
//            $user_info = $this->fetchUserInfo($access_token_info->access_token);
//            // メールゲット
//            $email_info = $this->fetchUserEmail($access_token_info->id_token, $line_login->channel_id);
//            // 登録されているか？
//            $user = User::find($user_info->userId);
//            if ($user) {
//                $user->display_name = $user_info->displayName;
//                $user->email = $email_info->email;
//                $user->picture_url = $user_info->pictureUrl;
//                $user->save();
//                Auth::login($user);
//            } else {
//                // 新規登録
//                $newUser = new User();
//                $newUser->vendor_id = $line_login->vendor_id;
//                $newUser->id = $user_info->userId;
//                $newUser->display_name = $user_info->displayName;
//                $newUser->email = $email_info->email;
//                $newUser->picture_url = $user_info->pictureUrl;
//                $newUser->save();
//                //そのままログイン
//                Auth::login($newUser);
//            }
//            return redirect(route('home'));
//        } catch (Throwable $e) {
//            print_r($e->getMessage());
//            exit;
//        }
//    }
//
//    /**
//     * @param $access_token
//     * @return mixed
//     */
//    private function fetchUserInfo($access_token)
//    {
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
//        curl_setopt($curl, CURLOPT_URL, self::LINE_PROFILE_API_URI . 'profile');
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        $res = curl_exec($curl);
//        curl_close($curl);
//        return json_decode($res);
//    }
//
//    /**
//     * @param string $id_token
//     * @param string $channel_id
//     * @return mixed
//     */
//    public function fetchUserEmail(string $id_token, string $channel_id)
//    {
//        $body = http_build_query([
//            'id_token' => $id_token,
//            'client_id' => $channel_id,
//        ]);
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, self::LINE_TOKEN_API_URI . 'verify');
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
//        $res = curl_exec($curl);
//        curl_close($curl);
//        return json_decode($res);
//
//
////        $body = http_build_query([
////            'id_token' => $id_token,
////            'client_id' => $channel_id,
////        ]);
////        $sh = <<< EOF
////  curl -X POST \
////  https://api.line.me/oauth2/v2.1/verify
////  -d '$body'
////EOF;
////        return json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
//    }
//
//
//    /**
//     * @param $code
//     * @param $callback_id
//     * @return mixed
//     */
//    public function fetchTokenInfo($code, $callback_id)
//    {
//        $line_login = LineLogin::where('callback', $callback_id)->first();
//        $headers = ['Content-Type: application/x-www-form-urlencoded'];
//        $post_data = array(
//            'grant_type' => 'authorization_code',
//            'code' => $code,
//            'redirect_uri' => url(route('login.line.callback', $line_login->callback)),
//            'client_id' => $line_login->channel_id,
//            'client_secret' => $line_login->channel_secret,
//        );
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_URL, self::LINE_TOKEN_API_URI . 'token');
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));
//
//        $res = curl_exec($curl);
//        curl_close($curl);
//
//        return json_decode($res);
//    }
//
////    private function sendRequest($base_uri, $method, $path, $headers, $form_params = null)
////    {
////        try {
////            $client = new Client($base_uri);
////            if ($form_params) {
////                $response = $client->request($method, $path, $form_params, $headers);
////            } else {
////                $response = $client->request($method, $path, $headers);
////            }
////        } catch (\Exception $ex) {
////            //　例外処理
////        }
////        $result_json = $response->getbody()->getcontents();
////        return json_decode($result_json);
////    }
////
////    private function createUser($user_info)
////    {
////        print_r($user_info);
////        exit;
////        try {
////            // 登録されているか？
////            $user = User::find($user_info->id);
////            if ($user) {
////                $user->display_name = $user_info->getName();
////                $user->email = $user_info->getEmail();
////                $user->picture_url = $user_info->avatar;
////                $user->save();
////                Auth::login($user);
////            } else {
////                // 新規登録
////                $newUser = new User();
////                $newUser->id = $userSocial->id;
////                $newUser->display_name = $userSocial->getName();
////                $newUser->email = $userSocial->getEmail();
////                $newUser->picture_url = $userSocial->avatar;
////                $newUser->save();
////                //そのままログイン
////                Auth::login($newUser);
////            }
////            return redirect(route('home'));
////        } catch (\Throwable $e) {
////            print_r($e->getMessage());
////            exit;
////        }
////    }
//
////    public function handleProviderCallback(Request $request, string $callback_id)
////    {
////        // state検証
////        $state_line = $request->input('state');
////        $state_cookie = Cookie::get('state');
////
////        // stateが異なる場合
////        if ($state_line !== $state_cookie) {
////            \Session::flash('flash_message', 'state検証エラー');
////            return redirect('/');
////        }
////
////        // エラーレスポンスが返って来た場合はエラーを返却
////        $error_description = $request->input('error_description');
////        if ($error_description != "") {
////            \Session::flash('flash_message', '権限が拒否されました');
////            return redirect('/');
////        }
////
////        // アクセストークンを発行する
////        $code = $request->input('code');
////        print_r($code);
////        exit;
////        try {
////
////            $userSocial = Socialite::driver('line')->stateless()->user();
////            print_r($userSocial);
////            exit;
////            // 登録されているか？
////            $user = User::find($userSocial->id);
////            if ($user) {
////                $user->display_name = $userSocial->getName();
////                $user->email = $userSocial->getEmail();
////                $user->picture_url = $userSocial->avatar;
////                $user->save();
////                Auth::login($user);
////            } else {
////                // 新規登録
////                $newUser = new User();
////                $newUser->id = $userSocial->id;
////                $newUser->display_name = $userSocial->getName();
////                $newUser->email = $userSocial->getEmail();
////                $newUser->picture_url = $userSocial->avatar;
////                $newUser->save();
////                //そのままログイン
////                Auth::login($newUser);
////            }
////            return redirect(route('home'));
////        } catch (\Throwable $e) {
////            print_r($e->getMessage());
////            exit;
////        }
////    }
//}
