<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessHours;
use App\Models\ChannelJoin;
use App\Models\Course;
use App\Models\LineMessage;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Throwable;

class ReservationController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $admin = Auth::guard('admin')->user();

        $today = Carbon::now()->format('Y-m-d');
        $shops = Shop::where('vendor_id', $admin->vendor_id)->get();
        $courses = Course::where('vendor_id', $admin->vendor_id)->get();
        $times_url = route('admin.reservation.times', $admin->id);
        $destroy_url = route('admin.reservation.destroy');
        $status_url = route('admin.reservation.change-status');
        $members = ChannelJoin::where('vendor_id', $admin->vendor_id)->get();
//        $start = new Carbon('first day of last month');
//        $end = new Carbon('last day of next month');
//        $date = [
//            'start' => $start->startOfMonth(),
//            'end' => $end->endOfMonth(),
//        ];
        $event_url = url(route('admin.reservation.setAllEvent'));// . http_build_query($date);
        return view('admin.reservation.index')->with([
            'event_url' => $event_url,
            'today' => $today,
            'status_url' => $status_url,
            'members' => $members,
            'shops' => $shops,
            'courses' => $courses,
            'times_url' => $times_url,
            'destroy_url' => $destroy_url
        ]);
    }

    /**
     * 個別イベント
     * @return \Illuminate\View\View
     */
    public function individual(): View
    {
        $admin = Auth::guard('admin')->user();
        $today = Carbon::now()->format('Y-m-d');
        $shops = Shop::where('vendor_id', $admin->vendor_id)->get();
        $courses = Course::where('vendor_id', $admin->vendor_id)->get();
        $times_url = route('admin.reservation.times', $admin->id);
        $destroy_url = route('admin.reservation.destroy');
        $status_url = route('admin.reservation.change-status');
        $close_url = route('admin.reservation.close');
        $store_rest_url = route('admin.reservation.storeRest');
        $members = ChannelJoin::members($admin->vendor_id);
//        $start = new Carbon('first day of last month');
//        $end = new Carbon('last day of next month');
//        $date = [
//            'start' => $start->startOfMonth(),
//            'end' => $end->endOfMonth(),
//        ];
        $event_url = url(route('admin.reservation.setEvent'));// . http_build_query($date);

        $business_hour = BusinessHours::where('vendor_id', Auth::user()->vendor_id)->first();
        $start_time = $business_hour ? $business_hour->start : config('const.DEFAULT_BUSINESS_HOURS.START');
        $end_time = $business_hour ? $business_hour->end : config('const.DEFAULT_BUSINESS_HOURS.END');
        return view('admin.reservation.individual')->with([
            'close_url' => $close_url,
            'today' => $today,
            'event_url' => $event_url,
            'status_url' => $status_url,
            'members' => $members,
            'shops' => $shops,
            'courses' => $courses,
            'times_url' => $times_url,
            'destroy_url' => $destroy_url,
            'store_rest_url' => $store_rest_url,
            'business_hour' => $business_hour,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $admin_id
     * @return array
     */
    public function times(Request $request, int $admin_id): array
    {
        return Reservation::getTimes($request->selectedDate, $admin_id);
    }

    /**
     * 休憩
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function storeRest(Request $request): array
    {
        Log::debug($request->all());
        try {
            $admin = Auth::user();
            // Carbon::parse($request->date)->format('Y-m-d')
            $start = Carbon::parse(Carbon::parse($request->close_date)->format('Y-m-d') . ' ' . $request->restStartTime);
            $end = Carbon::parse(Carbon::parse($request->close_date)->format('Y-m-d') . ' ' . $request->restEndTime);
            Log::debug($start);
            Log::debug($end);
            $reservation_id = Str::random(10);
            $reservation = new Reservation();
            $reservation->id = $reservation_id;
            $reservation->vendor_id = $admin->vendor_id;
            $reservation->admin_id = $admin->id;
            $reservation->status = config('const.RESERVATION_STATUS.REST.STATUS');
            $reservation->reservation_start = $start;
            $reservation->reservation_end = $end;
            $reservation->save();
            return [
                'status' => true,
                'data' => [
                    'id' => $reservation_id,
                    'title' => config('const.RESERVATION_STATUS.REST.LABEL'),
                    'start' => Carbon::parse($start)->format('Y-m-d H:i'),
                    'end' => Carbon::parse($end)->format('Y-m-d H:i'),
                    'borderColor' => config('const.RESERVATION_STATUS.REST.COLOR'),
                ],
                ',message' => '登録しました',
            ];
        } catch (Throwable $e) {
            Log::debug($e->getMessage());
            return [
                'status' => true,
                'data' => [],
                'message' => '失敗しました',
            ];
        }
    }

    /**
     * １日休み
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function close(Request $request): array
    {
        try {
            $admin = Auth::user();
            $start = Carbon::parse($request->close_date)->startOfDay();
            $end = Carbon::parse($request->close_date)->endOfDay();
            $reservation_id = Str::random(10);
            $reservation = new Reservation();
            $reservation->id = $reservation_id;
            $reservation->vendor_id = $admin->vendor_id;
            $reservation->admin_id = $admin->id;
            $reservation->status = config('const.RESERVATION_STATUS.CLOSE.STATUS');
            $reservation->reservation_start = $start;
            $reservation->reservation_end = $end;
            $reservation->save();
            return [
                'status' => true,
                'data' => [
                    'id' => $reservation_id,
                    'title' => config('const.RESERVATION_STATUS.CLOSE.LABEL'),
                    'start' => Carbon::parse($start)->format('Y-m-d'),
                    'end' => Carbon::parse($end)->format('Y-m-d'),
                ],
                ',message' => '登録しました',
            ];
        } catch (Throwable $e) {
            Log::debug($e->getMessage());
            return [
                'status' => true,
                'data' => [],
                'message' => '失敗しました',
            ];
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function setAllEvent(Request $request): array
    {
        $events = [];
        $admin = Auth::guard('admin')->user();
        $start = $this->formatDate($request->start);
        $end = $this->formatDate($request->end);
        $reservations = Reservation::where('vendor_id', $admin->vendor_id)->whereBetween('reservation_start', [$start, $end])->get();
        if ($reservations) {
            foreach ($reservations as $key => $reservation) {
                $events[$key]['id'] = $reservation->id;
//                $events[$key]['groupId'] = $reservation->admin_id;
                $events[$key]['title'] = $reservation->user->name . ' | ' . $reservation->course->name . '(' . $reservation->course->view_course_time . ') | ' . $reservation->shop->name;
                $events[$key]['start'] = $reservation->reservation_start;
                $events[$key]['end'] = $reservation->reservation_end;// Carbon::parse($reservation->reserved_at)->addMinutes($reservation->course->course_time)->format('Y-m-d H:i');
                // ステータス
                $status = reservation_status($reservation->status);
                $events[$key]['borderColor'] = $status['COLOR'];
            }
        }
        return $events;
    }

    /**
     * @param $date
     * @return array|string|string[]
     */
    public function formatDate($date)
    {
        return str_replace('T00:00:00+09:00', '', $date);
    }

    /**
     * 取得
     * admin/reservation/individual
     * @param \Illuminate\Http\Request $request
     * @return array
     * @throws \Exception
     */
    public function setEvent(Request $request): array
    {
        $events = [];
        // 範囲
        $admin = Auth::guard('admin')->user();
        $start = $this->formatDate($request->start);
        $end = $this->formatDate($request->end);
        // 予約を取得
        $reservations = Reservation::reserved()->where('vendor_id', $admin->vendor_id)->where('admin_id', $admin->id)->whereBetween('reservation_start', [$start, $end])->get();
        if ($reservations) {
            foreach ($reservations as $key => $reservation) {
                $events[$key]['id'] = $reservation->id;
//                $events[$key]['groupId'] = $reservation->admin_id;
                if ($reservation->status == config('const.RESERVATION_STATUS.CLOSE.STATUS')) {
                    $events[$key]['title'] = config('const.RESERVATION_STATUS.CLOSE.LABEL');
                    $events[$key]['start'] = Carbon::parse($reservation->start)->format('Y-m-d');
                    $events[$key]['end'] = Carbon::parse($reservation->end)->format('Y-m-d');
                } else if ($reservation->status == config('const.RESERVATION_STATUS.REST.STATUS')) {
                    $endDateTime = new Carbon($reservation->start);
                    $minute = $endDateTime->addMinutes($reservation->end);
                    //$events[$key]['title'] = '休憩 | ' . Carbon::parse($reservation->start)->format('H:i') . Carbon::parse($reservation->end)->format('H:i');  // config('const.RESERVATION_STATUS.CLOSE.LABEL');
                    $events[$key]['title'] = '休憩 | ' . $minute->format('H:i');
                    $events[$key]['start'] = $reservation->start;
                    $events[$key]['end'] = $reservation->end;

                } else {
                    $events[$key]['title'] = $reservation->user->name . ' | ' . $reservation->course->name . '(' . $reservation->course->view_course_time . ') | ' . $reservation->shop->name;
                    $events[$key]['start'] = $reservation->start;
                    $events[$key]['end'] = $reservation->end;
                }
                // ステータス
                $status = reservation_status($reservation->status);
                Log::debug($status);
                $events[$key]['borderColor'] = $status['COLOR'];
            }
        }
        return $events;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getViewEvent(Request $request): JsonResponse
    {
        $data = [];
        $reservation_id = $request->get('reservationId');
        $admin = Auth::user();
        $reservation = Reservation::reserved()->where('vendor_id', $admin->vendor_id)->where('id', $reservation_id)->first();
        if ($reservation) {
            $data = [
                'admin_id' => $reservation->admin_id,
                'category' => $reservation->category,
                'course_id' => $reservation->course_id,
                'course_name' => $reservation->course->name,
                'created_at' => $reservation->created_at,
                'id' => $reservation->id,
                'reservation_start' => Carbon::parse($reservation->start)->format('Y年m月d日 H:i'),
                'reservation_date' => $reservation->start_date,
                'shop_id' => $reservation->shop_id,
                'shop_name' => $reservation->shop->name,
                'status_id' => $reservation->status,
                'status' => $reservation->view_status,
                'updated_at' => $reservation->updated_at,
                'user_id' => $reservation->user_id,
                'user_name' => $reservation->user->name,
                'trainer_id' => $reservation->admin_id,
                'trainer_name' => $reservation->admin->name,
                'vendor_id' => $reservation->vendor_id,
            ];
        }
        return response()->json(['reservation' => $data]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvent(Request $request): JsonResponse
    {
        $data = [];
        $reservation_id = $request->get('reservationId');
        $admin = Auth::user();
        $reservation = Reservation::where('vendor_id', $admin->vendor_id)->where('id', $reservation_id)->first();
        if ($reservation) {
            $data = [
                'admin_id' => $reservation->admin_id,
                'category' => $reservation->category,
                'course_id' => $reservation->course_id,
                'created_at' => $reservation->created_at,
                'id' => $reservation->id,
                'reservation_start' => Carbon::parse($reservation->reservation_start)->format('Y-m-d H:i'),
                'shop_id' => $reservation->shop_id,
                'status' => $reservation->status,
                'updated_at' => $reservation->updated_at,
                'user_id' => $reservation->user_id,
                'vendor_id' => $reservation->vendor_id,
            ];
        }
        return response()->json(['reservation' => $data]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function store(Request $request): array
    {
        try {
            $course = Course::find($request->course);
            Log::debug('ここにきた111');
            Log::debug($course->time);
            Log::debug('ここにきた111');

            $reservation_start = Carbon::parse(Carbon::parse($request->date)->format('Y-m-d') . $request->time);
            $reservation_end = Carbon::parse($reservation_start)->addMinutes($course->time);
            $reservation_id = Str::random(10);

            $admin = Auth::user();
            $reservation = new Reservation();
            $reservation->id = $reservation_id;
            $reservation->vendor_id = $admin->vendor_id;
            $reservation->user_id = $request->user;
            $reservation->admin_id = $admin->id;
            $reservation->status = $request->status;
            $reservation->course_id = $request->course;
            $reservation->shop_id = $request->shop;
            $reservation->reservation_start = $reservation_start;
            $reservation->reservation_end = $reservation_end;
            $reservation->save();
            /////////////////////
            $user = User::find($request->user);
            $shop = Shop::find($request->shop);
            $title = $user->name . ' | ' . $course->name . '(' . $course->view_course_time . ') | ' . $shop->name;

            ////////////////////////////////////////////
            /// プッシュ通知
            $status = reservation_status($request->status);
            $messages = "予約を" . $status['LABEL'] . "としました。\n\n";
            $messages .= '【日時】' . "\n";
            $messages .= Carbon::parse($reservation->reservation_start)->format('Y年m月d日 H:i') . "\n";
            $messages .= '（' . $reservation->view_status . "）\n";
            $messages .= '【トレーナー】' . "\n";
            $messages .= $reservation->admin->name . "\n";
            $messages .= '【店舗】' . "\n";
            $messages .= $reservation->shop->name . "\n";
            $messages .= '【住所】' . "\n";
            $messages .= $reservation->shop->view_address . "\n";
            if ($reservation->shop->phone_number) {
                $messages .= '【電話番号】' . "\n";
                $messages .= $reservation->shop->phone_number . "\n";
            }
            $messages .= '【メニュー】' . "\n";
            $messages .= $reservation->course->name . "\n";
            $user_messageBuilder = new TextMessageBuilder($messages);
            // ラインメッセージ情報取得
            $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
            $httpClient = new CurlHTTPClient($line_message->channel_access_token);
            $bot = new LINEBot($httpClient, ['channelSecret' => $line_message->channel_secret]);
            $bot->pushMessage($reservation->user_id, $user_messageBuilder);
            ////////////////////////////////////////////
            /// プッシュ通知
            return [
                'status' => true,
                'data' => [
                    'id' => $reservation_id,
                    'title' => $title,
                    'start' => Carbon::parse($reservation_start)->format('Y-m-d H:i'),
                    'end' => Carbon::parse($reservation_end)->format('Y-m-d H:i'),
                    'borderColor' => $status['COLOR'],
                ],
                ',message' => '登録しました',
            ];
        } catch (Throwable $e) {
            return [
                'status' => true,
                'data' => [],
                'message' => '登録失敗：' . $e->getMessage()
            ];
        }
    }

    /**
     * @param string $reservation_id
     * @return \Illuminate\View\View
     */
    public function show(string $reservation_id): View
    {
        $admin = Auth::user();
        $reservation = Reservation::where('id', $reservation_id)->where('vendor_id', $admin->vendor_id)->first();
        return view('admin.reservation.show')->with(['reservation' => $reservation]);
    }

    /**
     * 新規予約処理
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function update(Request $request): array
    {
        Log::debug($request->all());
        try {
            $course = Course::find($request->course);
            $reservation_start = Carbon::parse($request->date . $request->time);
            $reservation_end = Carbon::parse($reservation_start)->addMinutes($course->course_time);

            $admin = Auth::user();
            $reservation = Reservation::where('id', $request->reservation_id)->where('vendor_id', $admin->vendor_id)->first();
            $reservation->user_id = $request->user;
//            $reservation->admin_id = $admin->admin_id;
            $reservation->status = $request->status;// config('const.RESERVATION_STATUS.FIXED.STATUS');
            $reservation->category = 20;
            $reservation->course_id = $request->course;
            $reservation->shop_id = $request->shop;
            $reservation->reservation_start = $reservation_start;
            $reservation->reservation_end = $reservation_end;
            $reservation->save();
            /////////////////////
            $user = User::find($request->user);
            $shop = Shop::find($request->shop);
            $title = $user->name . '|' . $course->name . '(' . $course->course_time . ')|' . $shop->name;
            $status = reservation_status($request->status);

            $status = reservation_status($request->status);
            $messages = "予約を" . $status['LABEL'] . "としました。\n\n";
            $messages .= '【日時】' . "\n";
            $messages .= Carbon::parse($reservation->reservation_start)->format('Y年m月d日 H:i') . "\n";
            $messages .= '（' . $reservation->view_status . "）\n";
            $messages .= '【トレーナー】' . "\n";
            $messages .= $reservation->admin->name . "\n";
            $messages .= '【店舗】' . "\n";
            $messages .= $reservation->shop->name . "\n";
            $messages .= '【住所】' . "\n";
            $messages .= $reservation->shop->view_address . "\n";
            if ($reservation->shop->phone_number) {
                $messages .= '【電話番号】' . "\n";
                $messages .= $reservation->shop->phone_number . "\n";
            }
            $messages .= '【メニュー】' . "\n";
            $messages .= $reservation->course->name . "\n";
            $user_messageBuilder = new TextMessageBuilder($messages);
            // ラインメッセージ情報取得
            $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
            $httpClient = new CurlHTTPClient($line_message->channel_access_token);
            $bot = new LINEBot($httpClient, ['channelSecret' => $line_message->channel_secret]);
            $bot->pushMessage($reservation->user_id, $user_messageBuilder);

            return [
                'status' => true,
                'data' => [
                    'id' => $request->reservation_id,
                    'title' => $title,
                    'start' => Carbon::parse($reservation_start)->format('Y-m-d H:i'),
                    'end' => Carbon::parse($reservation_end)->format('Y-m-d H:i'),
                    'borderColor' => $status['COLOR'],
                ],
                ',message' => '変更しました',
            ];
        } catch (Throwable $e) {
            return [
                'status' => true,
                'data' => [],
                'message' => '変更失敗：' . $e->getMessage()
            ];
        }
    }

    /**
     * ステータス変更
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function changeStatus(Request $request): array
    {
        Log::debug($request->all());
        try {
            $admin = Auth::user();
            $reservation = Reservation::where('id', $request->reservationId)->where('vendor_id', $admin->vendor_id)->first();
            if (!$reservation) {
                throw new Exception('予約が見つかりません : ' . $request->reservationId);
            }
            $reservation->status = $request->status;
            $reservation->save();
            /////////////////////
            $user = User::find($reservation->user_id);
            $course = Course::find($reservation->course_id);
            $shop = Shop::find($reservation->shop_id);
            $title = $user->name . '|' . $course->name . '(' . $course->course_time . ')|' . $shop->name;
            $status = reservation_status($request->status);
            // 確定通知
            if ($request->status == config('const.RESERVATION_STATUS.FIXED.STATUS')) {
                $messages = "予約が確定しました。\n\n";
                $messages .= '【日時】' . "\n";
                $messages .= Carbon::parse($reservation->reservation_start)->format('Y年m月d日 H:i') . "\n";
                $messages .= '（' . $reservation->view_status . "）\n";
                $messages .= '【トレーナー】' . "\n";
                $messages .= $reservation->admin->name . "\n";
                $messages .= '【店舗】' . "\n";
                $messages .= $reservation->shop->name . "\n";
                $messages .= '【住所】' . "\n";
                $messages .= $reservation->shop->view_address . "\n";
                if ($reservation->shop->phone_number) {
                    $messages .= '【電話番号】' . "\n";
                    $messages .= $reservation->shop->phone_number . "\n";
                }
                $messages .= '【メニュー】' . "\n";
                $messages .= $reservation->course->name . "\n";
                $user_messageBuilder = new TextMessageBuilder($messages);
                // ラインメッセージ情報取得
                $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
                $httpClient = new CurlHTTPClient($line_message->channel_access_token);
                $bot = new LINEBot($httpClient, ['channelSecret' => $line_message->channel_secret]);
                $bot->pushMessage($reservation->user_id, $user_messageBuilder);
            }
            return [
                'status' => true,
                'data' => [
                    'id' => $request->reservationId,
                    'title' => $title,
                    'start' => $reservation->reservation_start,
                    'end' => Carbon::parse($reservation->reservation_end),
                    'borderColor' => $status['COLOR'],
                ],
                ',message' => '変更しました',
            ];
        } catch (Throwable $e) {
            return [
                'status' => true,
                'data' => [],
                'message' => '更新失敗：' . $e->getMessage()
            ];
        }
    }

    /**
     * キャンセル
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function destroy(Request $request): array
    {
        try {
            $admin = Auth::user();
            $reservation = Reservation::where('id', $request->reservationId)->where('vendor_id', $admin->vendor_id)->first();
            if (!$reservation) {
                throw new Exception('予約がが見つかりません : ' . $request->reservationId);
            }
            $reservation->delete();
            /////////////////////////////////////////////
            /// 会員のプッシュ通知
            $messages = "予約がキャンセルされました。\n\n";
            $messages .= '【日時】' . "\n";
            $messages .= Carbon::parse($reservation->reservation_start)->format('Y年m月d日 H:i') . "\n";
            $messages .= '（' . $reservation->view_status . "）\n";
            $messages .= '【トレーナー】' . "\n";
            $messages .= $reservation->admin->name . "\n";
            $messages .= '【店舗】' . "\n";
            $messages .= $reservation->shop->name . "\n";
            $messages .= '【住所】' . "\n";
            $messages .= $reservation->shop->view_address . "\n";
            if ($reservation->shop->phone_number) {
                $messages .= '【電話番号】' . "\n";
                $messages .= $reservation->shop->phone_number . "\n";
            }
            $messages .= '【メニュー】' . "\n";
            $messages .= $reservation->course->name . "\n";
            $user_messageBuilder = new TextMessageBuilder($messages);
            // ラインメッセージ情報取得
            $line_message = LineMessage::where('vendor_id', $admin->vendor_id)->first();
            $httpClient = new CurlHTTPClient($line_message->channel_access_token);
            $bot = new LINEBot($httpClient, ['channelSecret' => $line_message->channel_secret]);
            $bot->pushMessage($reservation->user_id, $user_messageBuilder);
            /////////////////////
            return [
                'status' => true,
                'reservation_id' => $request->reservationId,
                ',message' => '削除しました',
            ];
        } catch (Throwable $e) {
            return [
                'status' => true,
                'data' => [],
                'message' => '削除失敗：' . $e->getMessage()
            ];
        }
    }
}
