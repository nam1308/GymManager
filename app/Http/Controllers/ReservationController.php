<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\ChannelJoin;
use App\Models\Course;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Mockery\Exception;
use SendGrid\Mail\Mail;
use SendGrid;
use Throwable;

class ReservationController extends Controller
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
    public function index(): View
    {
        // 予約があるか
        $now = Carbon::now();
        $reservations = Reservation::reserved()
            ->where('user_id', Auth::id())
            ->where('reservation_start', '>', $now)
            ->orderBy('reservation_start', 'ASC')->paginate(30, ['*']);
        return view('reservation.index')->with([
            'reservations' => $reservations,
        ]);
    }

    /**
     * @param $trainer_id
     * @return \Illuminate\View\View
     */
    public function create($trainer_id): View
    {
        $admin = Auth::user();
        $trainer = Admin::where(['vendor_id' => $admin->vendor_id, 'id' => $trainer_id])->first();
        $shops = Shop::where('vendor_id', $admin->vendor_id)->get();
        $courses = Course::where('vendor_id', $admin->vendor_id)->get();
        return view('reservation.create')->with([
            'trainer' => $trainer,
            'trainer_id' => $trainer_id,
            'shops' => $shops,
            'courses' => $courses,
            'times_url' => route('reservation.times', $trainer_id),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $trainer_id
     * @return array|array[]
     */
    public function times(Request $request, int $trainer_id): array
    {
        $data = [
            'times' => [],
        ];
        if (!$request->selectedDate) {
            return $data;
        }

        $admin = Auth::user();
        $trainer = Admin::where(['vendor_id' => $admin->vendor_id, 'id' => $trainer_id])->first();
        if (!$trainer) {
            return $data;
        }
        $reservations = Reservation::where('admin_id', $trainer_id)->get();
        Log::debug($reservations);
        $reservationDates[0] = Carbon::parse($request->selectedDate . ' 9:00');
        $t = strtotime('09:00');
        for ($i = 0; $i < 15 * 5 * 12; $i += 15) {
            $data['times'][] = date('H:i', strtotime("+{$i} minutes", $t));
        }
        return $data;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $trainer_id
     * @return array
     */
    public function store(Request $request, int $trainer_id): array
    {
        $data = [
            'status' => false,
            'url' => route('reservation.thanks', $trainer_id),
            'message' => '登録エラー',
        ];

        // 自分取得
        $user = Auth::user();
        if (!$user) {
            $data['message'] = '予約するにはログインしてください';
            return $data;
        }
        // トレーナーがいるか
        $trainer = Admin::getTrainer($user->vendor_id, $trainer_id);
        if (!$trainer) {
            $data['message'] = 'トレーナーが見つかりません';
            return $data;
        }
        $course = Course::find($request->course);
        if ($course) {
            $data['message'] = 'メニューが見つかりません';
            return $data;
        }

        $reservation_start = $request->date . ' ' . $request->time;
        $reservation_end = Carbon::parse($reservation_start)->addMinutes($course->course_time)->format('Y-m-d H:i');
        // 予約番号
        $reservation_id = Str::random(10);
        // 登録
        $reservation = new Reservation();
        $reservation->id = $reservation_id;
        $reservation->vendor_id = $user->vendor_id;
        $reservation->user_id = $user->id;
        $reservation->admin_id = $trainer->id;
        $reservation->status = 10; // 仮予約
        $reservation->category = 10;
        $reservation->course_id = $request->course;
        $reservation->shop_id = $request->shop;
        $reservation->reservation_start = $request->date . ' ' . $request->time;
        $reservation->reservation_end = $reservation_end;
        $reservation->save();
        $data['status'] = true;
        return $data;
    }

    /**
     * @param string $reservation_id
     * @return \Illuminate\View\View
     */
    public function show(string $reservation_id): View
    {
        $user = Auth::user();
        $reservation = Reservation::reserved()
            ->where('id', $reservation_id)
            ->where('user_id', $user->id)
            ->first();
        return view('reservation.show')->with([
            'reservation' => $reservation
        ]);
    }

    /**
     * @param int $trainer_id
     * @return \Illuminate\View\View
     */
    public function thanks(int $trainer_id): View
    {
        return view('reservation.thanks')->with([
            'trainer_id' => $trainer_id
        ]);
    }

    /**
     * @param string $reservation_id
     * @return array
     */
    public function cancel(string $reservation_id): array
    {
        $data = [
            'status' => false,
            'message' => '予約キャンセル失敗',
            'url' => ''
        ];
        try {
            // 予約があるか
            $reservation = Reservation::find($reservation_id);
            if (!$reservation) {
                throw new Exception('トークンが見つかりません');
            }
            // トレーナー
            $admin = Admin::where('id', $reservation->admin_id)->where('vendor_id', $reservation->vendor_id)->first();
            // チャネル登録されているか？
            $channel_join = ChannelJoin::where('user_id', $reservation->user_id)->where('vendor_id', $reservation->vendor_id)->first();
            if (!$channel_join) {
                throw new Exception('チャネル登録されてない');
            }
            // ユーザー
            $user = User::where('id', $reservation->user_id)->first();
            if (!$user) {
                throw new Exception('ユーザーが見つかりません');
            }
            $course = Course::where('id', $reservation->course_id)->where('vendor_id', $reservation->vendor_id)->first();
            $shop = Shop::where('id', $reservation->shop_id)->where('vendor_id', $reservation->vendor_id)->first();
            // 削除
            $reservation->delete();
            $data = [
                'reservation' => Carbon::parse($reservation->reservation_start)->format('Y年m月d日 H:i'),
                'name' => $user->display_name,
                'course_name' => $course->name,
                'shop_name' => $shop->name,
            ];
            // トレーナーにメール
            $email = new Mail();
            $email->setFrom("info@aporze.com", getenv('APP_TITLE_JA'));
            $email->setSubject(getenv('APP_TITLE_JA') . "予約がキャンセルされました");
            $email->addTo($admin->email, $admin->name);
            $email->addContent("text/html", strval(
                view('emails.user_reservation_cancel', compact('data'))
            ));
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            Log::debug(json_encode($response));

            $data['status'] = true;
            $data['message'] = '予約キャンセルしました';
            $data['url'] = route('reservation');
            return $data;
        } catch (Throwable $e) {
            $data['message'] = $e->getMessage();
            return $data;
        }
    }
}
