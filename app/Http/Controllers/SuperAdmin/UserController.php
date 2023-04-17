<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ChannelJoin;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $query = ChannelJoin::query();
        if (isset($request->display_name)) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('display_name', 'like', '%' . $request->display_name . '%');
            });
        }
        if (isset($request->name)) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            });
        }
        if (isset($request->email)) {
            $query->whereHas('user', function ($query) use ($request) {
                $query->where('email', 'like', '%' . $request->email . '%');
            });
        }
        $channel_joins = $query->orderBy('id', 'DESC')->paginate(20, ['*']);
        return view('super-admin.user.index', compact('channel_joins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('super-admin.user.create');
    }

    /**
     * @param $user_id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($user_id): View
    {
        $user = User::find($user_id);
        $reservations = Reservation::where('user_id', $user_id)->paginate($this->limit, ['*']);
        return view('super-admin.user.show',
            compact('user'),
            compact('reservations')
        );
    }

    /**
     * @param $user_Id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($user_Id): View
    {
        $user = User::find($user_Id);
        return view('super-admin.user.edit',
            compact('user')
        );
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

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($request) {
            $stream = fopen('php://output', 'w');
            // 文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            $query = ChannelJoin::query();
            if (isset($request->display_name)) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('display_name', 'like', '%' . $request->display_name . '%');
                });
            }
            if (isset($request->name)) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                });
            }
            if (isset($request->email)) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('email', 'like', '%' . $request->email . '%');
                });
            }
            $users = $query->orderBy('id', 'DESC')->get();
            if (empty($users[0])) {
                fputcsv($stream, [
                    'データが存在しませんでした。',
                ]);
            } else {
                fputcsv($stream, ['会員ID', 'ライン名', '登録名', '電話番号', 'メールアドレス', '登録日']);
                foreach ($users as $user) {
                    fputcsv($stream, $this->_csvRow($user));
                }
            }
            fclose($stream);
        });
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('content-disposition', 'attachment; filename=会員一覧.csv');
        return $response;
    }

    private function _csvRow($row): array
    {
        return [
            $row->user->id,
            $row->user->display_name,
            $row->user->name,
            $row->user->phone_number,
            $row->user->email,
            $row->user->created_at,
        ];
    }
}
