<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\LineMessage;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use SendGrid;
use SendGrid\Mail\Mail;

class ReminderScheduleBefore2Hours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:2hours';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '２時間前になったらお知らせする';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     * @throws \SendGrid\Mail\TypeException
     */
    public function handle(): bool
    {
        $time = 2;
        $time_message = $time . '時間後に予約が入っております';

        $dt = new Carbon();
        $target_date = $dt->addHour($time)->format('Y-m-d H:i') . ':00';
        Log::debug('2時間後の時間');
        Log::debug($target_date);
        Log::debug('2時間後の時間');
        $reservations = Reservation::where('reservation_start', $target_date)->where('status', config('const.RESERVATION_STATUS.FIXED.STATUS'))->get();
        if ($reservations) {
            foreach ($reservations as $reservation) {
                // メッセージチャンネル取得
                $line_message = LineMessage::where('vendor_id', $reservation->vendor_id)->first();
                $messages = $time_message . "\n\n";
                $messages .= '【日時】' . "\n";
                $messages .= Carbon::parse($reservation->reservation_start)->format('Y年m月d日 H:i') . "\n";
                $messages .= '（' . $reservation->view_status . "）\n";
                $messages .= '【トレーナー】' . "\n";
                $messages .= $reservation->admin->name . "\n";
                $messages .= '【店舗】' . "\n";
                $messages .= $reservation->shop->name . "\n";
                $messages .= '【メニュー】' . "\n";
                $messages .= $reservation->course->name . "\n";
                $httpClient = new CurlHTTPClient($line_message->channel_access_token);
                $bot = new LINEBot($httpClient, ['channelSecret' => $line_message->channel_secret]);
                $bot->pushMessage($reservation->user_id, new TextMessageBuilder($messages));
                // 管理者を取得
                $admin = Admin::where('id', $reservation->admin_id)->first();
                if (!$admin) {
                    continue;
                }
                //////////////////////////////////
                /// メール送信
                $data['time_message'] = $time_message;
                $data['reservation_start'] = $reservation->reservation_start;
                $data['course_name'] = $reservation->course->name;
                $data['store_name'] = $reservation->shop->name;
                $data['name'] = $reservation->user->name;
                $email = new Mail();
                $email->setFrom('info@aporze.com', getenv('APP_TITLE_JA'));
                $email->setSubject(getenv('APP_TITLE_JA') . $time_message);
                $email->addTo($admin->email);
                $email->addContent("text/html", strval(
                    view('emails.reservation_tentative_reminder', compact('data'))
                ));
                $sendgrid = new SendGrid(getenv('SENDGRID_API_KEY'));
                $sendgrid->send($email);
            }
        }
        return true;
    }
}
