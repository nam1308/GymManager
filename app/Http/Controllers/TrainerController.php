<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\BasicSetting;
use App\Models\ChannelJoin;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TrainerController extends Controller
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
     * @param string $vendor_id
     * @return \Illuminate\View\View
     */
    public function index(string $vendor_id): View
    {
        // チャンネル登録されているか？
        $channel_join = ChannelJoin::where('vendor_id', $vendor_id)->where('user_id', Auth::id())->first();
        // トレーナー取得
        $trainers = Admin::where('vendor_id', $vendor_id)->trainer()->get();//->where('trainer_role', config('const.TRAINER_ROLE.TRAINER.STATUS'))->get();
        $basic_setting = BasicSetting::where('vendor_id', $vendor_id)->first();
        return view('trainer.index')->with([
            'channel_join' => $channel_join,
            'trainers' => $trainers,
            'basic_setting' => $basic_setting,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $admin_id
     * @return array
     */
    public function store(Request $request, int $admin_id): array
    {
        $data = [
            'status' => false,
            'url' => route('trainer.thanks', $admin_id),
            'message' => '登録エラー',
        ];

        $admin = Admin::find($admin_id);
        if ($admin) {

        }

        $reservation_id = Str::random(10);

        $reservation = new Reservation();
        $reservation->id = $reservation_id;
        $reservation->vendor_id = $admin->vendor_id;
        $reservation->user_id = Auth::guard('user')->id();
        $reservation->admin_id = $admin->id;
        $reservation->status = 10;
        $reservation->category = 10;
        $reservation->course_id = 1;
        $reservation->store_id = 1;
        $reservation->reservation_start = Carbon::parse($request->date . ' ' . $request->time);
        $reservation->save();
        $data['status'] = true;
        return $data;
    }

    /**
     * @param string $vendor_id
     * @param int $trainer_id
     * @return \Illuminate\View\View
     */
    public function show(string $vendor_id, int $trainer_id): View
    {
        $channel_join = ChannelJoin::join(Auth::id(), $vendor_id)->first();
        $trainer = Admin::where('vendor_id', $vendor_id)->where('id', $trainer_id)->trainer()->first();
        return view('trainer.show')->with([
            'channel_join' => $channel_join,
            'trainer' => $trainer,
            'times_url' => route('trainer.show', $trainer_id),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $admin_id
     * @return array|array[]
     */
    public function times(Request $request, int $admin_id): array
    {
        $data = [
            'times' => [],
        ];
        if (!$request->selectedDate) {
            return $data;
        }

        $trainer = Admin::find($admin_id);
        if (!$trainer) {
            return $data;
        }
        $reservations = Reservation::find($admin_id);
        $reservationDates[0] = Carbon::parse($request->selectedDate . ' 9:00');
        $t = strtotime('09:00');
        for ($i = 0; $i < 15 * 5 * 12; $i += 15) {
            $data['times'][] = date('H:i', strtotime("+{$i} minutes", $t));
        }
        return $data;
    }

    /**
     * @param int $admin_id
     * @return \Illuminate\View\View
     */
    public function thanks(int $admin_id): View
    {
        return view('reservation.thanks')->with(['admin_id' => $admin_id]);
    }
}
