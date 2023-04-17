<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function edit(): View
    {
        $user = Auth::user();
        $user['birthday_year'] = Carbon::parse($user['birthday'])->format('Y');
        $user['birthday_month'] = Carbon::parse($user['birthday'])->format('m');
        $user['birthday_day'] = Carbon::parse($user['birthday'])->format('d');
        return view('user.edit')
            ->with(['user' => $user]);
    }

    /**
     * @param \App\Http\Requests\UpdateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request): RedirectResponse
    {
        $birthday = Carbon::parse($request->birthday_year . '-' . $request->birthday_month . '-' . $request->birthday_day)->format('Y-m-d');
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->name_kana = $request->name_kana;
        $user->phone_number = $request->phone_number;
        $user->birthday = $birthday;
        $user->gender_id = $request->gender_id;
        $user->birthday_search = sprintf('%02d', $request->birthday_month) . '-' . sprintf('%02d', $request->birthday_day);
        $user->save();
        return redirect(route('user.edit'))
            ->with('flash_message_success', '保存しました');
    }

    /**
     * アカウント削除（削除）
     */
    public function destroy()
    {
        //
    }
}
