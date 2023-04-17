<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\ChannelJoin;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserMemo;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class UserController extends Controller
{
    public int $limit = 30;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $admin = Auth::user();
        $channel_joins = ChannelJoin::where('vendor_id', $admin->vendor_id)->paginate($this->limit, ['*']);
        return view('admin.user.index', compact('channel_joins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * @param \App\Http\Requests\CreateUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $admin = Auth::user();
                $user_id = Str::random(30);
                $channel = new ChannelJoin();
                $channel->vendor_id = $admin->vendor_id;
                $channel->user_id = $user_id;
                $channel->save();

                $user = new User();
                $user->id = $user_id;
                $user->name = $request->name;
                $user->display_name = $request->name;
                $user->name_kana = $request->name_kana;
                $user->phone_number = $request->phone_number;
                $user->save();

                $user_memo = new UserMemo();
                $user_memo->vendor_id = $admin->vendor_id;
                $user_memo->user_id = $user_id;
                $user_memo->memo = NULL;
                $user_memo->save();
                return $user;
            });
            return redirect(route('admin.user'))
                ->with('flash_message_success', '保存しました');
        } catch (Throwable $e) {
            return back()->with('flash_message_warning', $e->getMessage())->withInput();
        }

//        $birthday = User::createBirthdayFormat($request->birthday_year, $request->birthday_month, $request->birthday_day);
//        $new_user = User::create([
//            'sei' => $request->sei,
//            'mei' => $request->mei,
//            'sei_kana' => $request->sei_kana,
//            'mei_kana' => $request->mei_kana,
//            'email' => $request->email,
//            'birthday' => $birthday,
//            'gender_id' => $request->gender_id,
//            'phone_number_1' => $request->phone_number_1,
//            'phone_number_2' => $request->phone_number_2,
//            'phone_number_3' => $request->phone_number_3,
//            'cellphone_number_1' => $request->cellphone_number_1,
//            'cellphone_number_2' => $request->cellphone_number_2,
//            'cellphone_number_3' => $request->cellphone_number_3,
//            'postal_code' => $request->postal_code
//            'prefecture_id' => $request->prefecture_id,
//            'municipality' => $request->municipality,
//            'address_building_name' => $request->address_building_name,
//            'password' => bcrypt($request->password),
//            'deleted_at' => Carbon::now(),
//            'vendor_id' => 0
//        ]);
//        $new_user->save();
//        return redirect(route('admin.user.show', $new_user->id))
//            ->with('flash_message_success', '保存しました');
    }

    /**
     * @param string $user_id
     * @return \Illuminate\Contracts\View\View
     */
    public function show(string $user_id): View
    {
        $admin = Auth::user();
        $channel_join = ChannelJoin::where('vendor_id', $admin->vendor_id)->where('user_id', $user_id)->first();
        $reservations = Reservation::where('vendor_id', $admin->vendor_id)->where('user_id', $user_id)->get();
        $memo = UserMemo::where('vendor_id', $admin->vendor_id)->where('user_id', $user_id)->first();
        return view('admin.user.show')->with([
            'memo' => $memo,
            'channel_join' => $channel_join,
            'reservations' => $reservations,
        ]);
    }

    /**
     * @param string $user_id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(string $user_id): View
    {
        $admin = Auth::user();
        $channel_join = ChannelJoin::join($user_id, $admin->vendor_id)->first();
        $user = User::find($user_id);
        return view('admin.user.edit')->with([
            'user', $user,
            'channel_join' => $channel_join,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $user_id): RedirectResponse
    {
        $user = User::find($user_id);
        if (!$user) {
            return redirect(route('admin.user'))->with('flash_message_warning', '会員がみつかりませんでした');
        }
        // 誕生日
        $birthday = Carbon::parse($request->birthday_year . '-' . $request->birthday_month . '-' . $request->birthday_day)->format('Y-m-d');
        // 固定電話
        $phone_numbers = explode('-', $request->phone_number);
        // 携帯電話
        $cellphone_numbers = $request->cellphone_number ? explode('-', $request->cellphone_number) : null;
        // 名前
        $user->sei = $request->sei;
        $user->mei = $request->mei;
        // 名前（カナ）
        $user->sei_kana = $request->sei_kana;
        $user->mei_kana = $request->mei_kana;
        // 誕生日
        $user->birthday = $birthday;
        // 性別
        $user->gender_id = $request->gender_id;
        // 固定電話
        $user->phone_number_1 = $phone_numbers[0];
        $user->phone_number_2 = $phone_numbers[1];
        $user->phone_number_3 = $phone_numbers[2];
        // 携帯番号
        $user->cellphone_number_1 = $cellphone_numbers[0];
        $user->cellphone_number_2 = $cellphone_numbers[1];
        $user->cellphone_number_3 = $cellphone_numbers[2];
        // 郵便番号
        $user->postal_code = $request->postal_code;
        $user->prefecture_id = $request->prefecture_id;
        $user->municipality = $request->municipality;
        $user->address_building_name = $request->address_building_name ? $request->address_building_name : null;
        $user->company_name = $request->company_name ? $request->company_name : null;
        $user->save();
        return redirect(route('admin.user.show', $user_id))
            ->with('flash_message_success', '保存しました');
    }

    /**
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($user_id): RedirectResponse
    {
        $user = User::find($user_id);
        if ($user) {
            $user->delete();
            return redirect(route('admin.user'))
                ->with('flash_message_success', $user->view_full_name . 'さんを削除しました');
        }
        return redirect(route('admin.user'))
            ->with('flash_message_success', '会員が見つからないため削除できません');
    }

    /**
     * 会員リストアする
     * @param $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function active($user_id): RedirectResponse
    {
        $user = User::onlyTrashed()->when('id', $user_id)->get();
        if ($user) {
            User::onlyTrashed()->when('id', $user_id)->restore();
            return redirect(route('admin.user'))
                ->with('flash_message_success', $user->view_full_name . 'さんをリストアしました');
        }
        return redirect(route('admin.user'))
            ->with('flash_message_danger', $user->view_full_name . 'さんをリストア失敗しました');

    }
}
