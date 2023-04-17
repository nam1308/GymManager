<?php

namespace App\Services\Line;

use App\Models\LineMessage;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

class ReservationService
{

    private $bot;
    private LineMessage $line_message;

    public function __construct(LINEBot $bot, LineMessage $line_message)
    {
        $this->bot = $bot;
        $this->line_message = $line_message;
    }

    public function getReservations(TextMessage $event): string
    {
        $messages = '';
        $now = Carbon::today();

        // #68 修正
        $reservations = Reservation::where('user_id', $event->getUserId())->where('vendor_id', $this->line_message->vendor_id)->where('reservation_start', '>=', $now)->get();
        // $reservations = Reservation::where('user_id', $event->getUserId())->where('vendor_id', $this->line_message->vendor_id)->get();
        Log::debug($reservations);
        if (count($reservations) > 0) {
            foreach ($reservations as $key => $reservation) {
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
                $messages .= $reservation->shop->view_address . "\n";
                $messages .= '【メニュー】' . "\n";
                $messages .= $reservation->course->name . "\n";
                if (count($reservations) != $key + 1) {
                    $messages .= "-------------------\n";
                }
            }
            return $messages;
        }
        return '現在、お客様のご予約はございません。';
    }
}
