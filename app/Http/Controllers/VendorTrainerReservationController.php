<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReservation;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Reservation;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;
use SendGrid;
use SendGrid\Mail\Mail;

class VendorTrainerReservationController extends Controller
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

    /*
     * @param string $vendor_id
     * @param int $admin_id
     * @return \Illuminate\View\View
     */
    public function show(string $vendor_id, int $admin_id)
    {
        $user = Auth::user();
        if (!$user->name) {
            return redirect(route('user.edit'))->with('flash_message_success', '予約するには必ずお客様情報を入力してください');
        }
        $trainer = Admin::where(['vendor_id' => $vendor_id, 'id' => $admin_id])->first();
        $shops = Shop::where('vendor_id', $vendor_id)->get();
        $courses = Course::where('vendor_id', $vendor_id)->get();
        $closes = Reservation::getCloses($vendor_id, $admin_id);//select('reservation_start')->where('vendor_id', $vendor_id)->where('status', config('const.RESERVATION_STATUS.CLOSE.STATUS'))->get()->all();
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        return view('vendor-trainer-reservation.show')->with([
            'tomorrow' => $tomorrow,
            'closes' => $closes,
            'trainer' => $trainer,
            'trainer_id' => $admin_id,
            'vendor_id' => $vendor_id,
            'shops' => $shops,
            'courses' => $courses,
            'times_url' => route('channel.trainer.reservation.times', [$vendor_id, $admin_id]),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param string $vendor_id
     * @param int $admin_id
     * @return array|array[]
     */
    public function times(Request $request, string $vendor_id, int $admin_id): array
    {
        return Reservation::getTimes($request->selectedDate, $admin_id);
    }

    /**
     * @param string $vendor_id
     * @param int $admin_id
     * @return \Illuminate\View\View
     */
    public function thanks(string $vendor_id, int $admin_id): View
    {
        return view('vendor-trainer-reservation.thanks')->with([
            'vendor_id' => $vendor_id,
            'admin_id' => $admin_id
        ]);
    }

    /**
     * ユーザーから予約が入る処理
     * @param \App\Http\Requests\CreateReservation $request
     * @param string $vendor_id
     * @param int $trainer_id
     * @return array
     */
    public function store(CreateReservation $request, string $vendor_id, int $trainer_id): array
    {
        Log::debug($request);
        $data = [
            'status' => false,
            'url' => route('channel.trainer.reservation.thanks', [$vendor_id, $trainer_id]),
            'message' => '',
        ];

        try {
            // トレーナーがいるか
            $admin = Admin::getTrainer($vendor_id, $trainer_id);
            if (!$admin) {
                $data['message'] = 'トレーナーが見つかりません';
                return $data;
            }
            // メニュー
            $course = Course::find($request->course);
            // お店
            $shop = Shop::find($request->shop);
            // 開始日
            $reservation_start = Carbon::parse($request->date . ' ' . $request->time)->format('Y-m-d H:i');
            // 終了日
            $reservation_end = Carbon::parse($reservation_start)->addMinutes($course->course_time)->format('Y-m-d H:i');
            // 予約番号
            $reservation_id = Str::random(10);
            // 登録
            $reservation = new Reservation();
            $reservation->id = $reservation_id;
            $reservation->vendor_id = $admin->vendor_id;
            $reservation->user_id = Auth::guard('user')->id();
            $reservation->admin_id = $admin->id;
            $reservation->status = 10; // 仮予約
            $reservation->category = 10;
            $reservation->course_id = $request->course;
            $reservation->shop_id = $request->shop;
            $reservation->reservation_start = $reservation_start;
            $reservation->reservation_end = $reservation_end;
            $reservation->note = $request->note;
            $reservation->save();

            $reservation_data = [
                'reservation_start' => $reservation_start,
                'course_name' => $course->name,
                'store_name' => $shop->name,
                'name' => Auth::guard('user')->user()->display_name,
            ];
            $email = new Mail();
            $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
            $email->setSubject(getenv('APP_TITLE_JA') . "仮予約がはいりました");
            $email->addTo($admin->email);
            $email->addContent("text/html", strval(
                view('emails.reservation_tentative', compact('reservation_data'))
            ));
            $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
            $response = $sendgrid->send($email);
            Log::debug(json_encode($response));
            $data['status'] = true;
            $data['message'] = 'success';
            return $data;
        } catch (Throwable $e) {
            $data['message'] = '登録エラー' . $e->getMessage();
            return $data;
        }
    }
}
