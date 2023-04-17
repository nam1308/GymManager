<?php

namespace App\Console\Commands;

use App\Models\LineMessage;
use App\Models\NotificationBirthday;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class BirthdayCelebration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'segment:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '会員の誕生日がきたらお祝いプッシュを送る';

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
     */
    public function handle(): bool
    {
        // 誕生日をセットしているベンダーを取得
        $birthdays = NotificationBirthday::whereNotNull('message')->get();
        if (!$birthdays) {
            return false;
        }
        // 明日の日付
        $tomorrow = Carbon::tomorrow()->format('m-d');
        Log::debug($tomorrow);
        foreach ($birthdays as $birthday) {
            // メッセージチャンネル取得
            $line_message = LineMessage::where('vendor_id', $birthday->vendor_id)->first();
            if (!$line_message) {
                return false;
            }
            //$channel_joins = ChannelJoin::where('vendor_id', $birthday->vendor_id)->get();
            $users = DB::table('channel_joins')
                ->join('users', 'users.id', '=', 'channel_joins.user_id') //->ChannelJoin::where('vendor_id', $birthday->vendor_id)->get();
                ->where('vendor_id', $birthday->vendor_id)
                ->where('users.birthday_search', $tomorrow)
//                ->whereBetween(DB::raw("(DATE_FORMAT(users.birthday,'%m-%d'))"), [$tomorrow, $tomorrow])
                ->get();
            if (!$users) {
                return false;
            }
            foreach ($users as $user) {
                $messages = $user->name . "様\n\n";
                $messages .= $birthday->message;
                $httpClient = new CurlHTTPClient($line_message->channel_access_token);
                $bot = new LINEBot($httpClient, ['channelSecret' => $line_message->channel_secret]);
                $bot->pushMessage($user->id, new TextMessageBuilder($messages));
            }
        }
        return true;
    }
}
