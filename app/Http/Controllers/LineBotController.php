<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\LineMessage;
use App\Models\LineRichMenu;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Line\FollowService;
use App\Services\Line\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use App\Services\Line\ReceiveLocationService;
use App\Services\Line\ReceiveTextService;
use App\Services\Line\UnFollowService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\PostbackEvent;
use LINE\LINEBot\Event\UnfollowEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\SignatureValidator;
use Illuminate\Http\Request;
use LINE\LINEBot;
use Exception;

class LineBotController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param string $webhook_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request, string $webhook_id): JsonResponse
    {
        Log::debug("---------------------------");
        Log::debug($webhook_id);
        Log::debug("---------------------------");
        try {
            //////////////////////////////////////////////////////
            /// ベンダーを取得する
            $line_message = LineMessage::where('callback', $webhook_id)->first();
            if (!$line_message) {
                throw new Exception('データーが見つかりませんでした。システム利用申請をしてない可能性があります。#err5383492');
            }
            if ($line_message->status != config('const.LINE_STATUS.ACCOMPLICE.STATUS') && $line_message->status != config('const.LINE_STATUS.ENTERED.STATUS')) {
                throw new Exception('ステータスが有効になっておりません。 #err33523');
            }
            //////////////////////////////////////////////////////
            /// シグネチャー取得
            $signature = $request->headers->get(HTTPHeader::LINE_SIGNATURE);
            if (!SignatureValidator::validateSignature($request->getContent(), $line_message->channel_secret, $signature)) {
                throw new Exception('不正なアクセス #err345223');
            }
            //////////////////////////////////////////////////////
            /// ラインボット
            $httpClient = new CurlHTTPClient($line_message->channel_access_token);
            $line_bot = new LINEBot($httpClient, ['channelSecret' => $line_message->channel_secret]);
            //////////////////////////////////////////////////////
            /// イベント
            $events = $line_bot->parseEventRequest($request->getContent(), $signature);
            if (!$events) {
                throw new Exception('イベントデーターが空です #err538273');
            }
            //////////////////////////////////////////////////////
            ///
            /// イベント
            foreach ($events as $event) {
                $reply_token = $event->getReplyToken();
                $reply_message = 'その操作はサポートしてません。.[' . get_class($event) . '][' . $event->getType() . ']';

//                // チャンネル登録があるか？
//                $channel_join = ChannelJoin::where('vendor_id', $line_message->vendor_id)->where('user_id', $event->getUserId())->first();
//                if (!$channel_join) {
//                    return false;
//                }
//
                // リッチメニューID取得
//                $richmeun = $this->createNewRichmenu($line_message->channel_access_token);
                switch (true) {
                    //////////////////////////////////////////////////////
                    ///
                    /// 友達登録＆ブロック解除
                    case $event instanceof FollowEvent:
                        Log::debug('################ FollowEvent');
                        $service = new FollowService($line_bot, $line_message);
                        $result = $service->execute($event);
                        if ($result) {
                            $this->createNewRichmenu($event, $line_message);
                        }
                        // フォローしたユーザーに最初のメッセージ
                        $text_message = new TextMessageBuilder($this->followEventMessage());
                        $line_bot->replyMessage($event->getReplyToken(), $text_message);
                        break;
                    //////////////////////////////////////////////////////
                    ///
                    /// メッセージ受信
                    case $event instanceof TextMessage:
                        Log::debug('################ TextMessage');
                        $service = new ReceiveTextService($line_bot, $line_message);
                        $result = $service->execute($event);

                        //////////////////////////////////////
                        /// トレーナー
                        if ($event->getText() === 'トレーナー予約') {
                            if ($event->getText()) {
                                $user = User::find($event->getUserId());
                                if (!$user) {
                                    $this->profileCheck($line_bot, $reply_token);
                                    break;
                                }
                                if (!$user->name || !$user->name_kana || !$user->birthday || !$user->gender_id) {
                                    $this->profileCheck($line_bot, $reply_token);
                                    break;
                                }
                            }
                            $columns = [];
                            $trainers = Admin::where('vendor_id', $line_message->vendor_id)->where('trainer_role', 10)->limit(10)->get();
                            if (count($trainers) > 0) {
                                foreach ($trainers as $trainer) {
                                    $columns[] = $this->createTrainerCarousel($trainer);
                                }
                                // カラムの配列を組み合わせてカルーセルを作成する
                                $carousel = new CarouselTemplateBuilder($columns);
                                Log::debug(json_encode($carousel));
                                // カルーセルを追加してメッセージを作る
                                $carousel_message = new TemplateMessageBuilder("トレーナー選択", $carousel);
                                $line_bot->replyMessage($event->getReplyToken(), $carousel_message);
                            } else {
                                $line_bot->replyMessage($reply_token, new TextMessageBuilder('トレーナーが見つかりません'));
                            }
                        }
                        //////////////////////////////////////
                        /// 予約
                        if ($event->getText() === '予約確認') {
                            if ($event->getText()) {
                                $user = User::find($event->getUserId());
                                if (!$user) {
                                    $this->profileCheck($line_bot, $reply_token);
                                    break;
                                }
                                if (!$user->name || !$user->name_kana || !$user->birthday || !$user->gender_id) {
                                    $this->profileCheck($line_bot, $reply_token);
                                    break;
                                }
                            }
                            // 予約をチェック
                            $service = new ReservationService($line_bot, $line_message);
                            $result_message = $service->getReservations($event);
                            $line_bot->replyMessage($reply_token, new TextMessageBuilder($result_message));
                        }
                        //////////////////////////////////////
                        /// メニュー削除
                        if ($event->getText() === 'delete ' || $event->getText() === 'delete') {
                            LineRichMenu::where('vendor_id', $line_message->vendor_id)->delete();
                            // リスト取得
                            $rich_menu_list = $this->getListOfRichmenu($line_message->channel_access_token);
                            if ($rich_menu_list) {
                                foreach ($rich_menu_list as $rich_data) {
                                    foreach ($rich_data as $r) {
                                        // 削除
                                        $result = $this->deleteRichmenu($line_message->channel_access_token, $r['richMenuId']);
                                    }
                                }
                            }
                            $line_bot->replyMessage($reply_token, new TextMessageBuilder(json_encode('ok')));
                        }

                        if ($event->getText() === '管理画面') {
                            $this->adminUrl($line_bot, $reply_token);
                        }

                        //////////////////////////////////////
                        /// メニュ一覧
                        if ($event->getText() == 'list' || $event->getText() == 'list ') {
                            $result = $this->getListOfRichmenu($line_message->channel_access_token);
                            Log::debug('CCCCCCCCCCCCCCCCc');
                            Log::debug($result);
                            Log::debug('CCCCCCCCCCCCCCCCc');
                            $line_bot->replyMessage($reply_token, new TextMessageBuilder(count($result['richmenus'])));
                        }
                        //////////////////////////////////////
                        /// メニュー登っj録
                        if ($event->getText() == 'set' || $event->getText() == 'set ') {
                            $this->setRichmenu($event, $line_message);
                        }
                        //////////////////////////////////////
                        /// メニュー新規作成
                        if ($event->getText() == 'create ' || $event->getText() == 'create') {
                            $result = $this->createNewRichmenu($event, $line_message);
                            if ($result == 'success') {
                                $line_bot->replyMessage($reply_token, new TextMessageBuilder('作成しました。一度チャンネルから抜けてください'));
                            } else {
                                $line_bot->replyMessage($reply_token, new TextMessageBuilder('作成失敗。システム会社に連絡してください'));
                            }
                        }
                        break;
                    //////////////////////////////////////////////////////
                    ///
                    /// ロケーション受信
                    case
                        $event instanceof LocationMessage:
                        Log::debug('################ LocationMessage');
                        $service = new ReceiveLocationService($line_bot);
                        $result = $service->execute($event);
                        break;
                    //////////////////////////////////////////////////////
                    ///
                    /// 選択肢とか選んだ時に受信するイベント
                    case $event instanceof PostbackEvent:
                        Log::debug('################ PostbackEvent');
                        break;
                    //////////////////////////////////////////////////////
                    ///
                    /// ブロック
                    case $event instanceof UnfollowEvent:
                        Log::debug('################ UnfollowEvent');
                        $service = new UnFollowService($line_bot, $line_message);
                        $result = $service->execute($event);
                        break;
                    default:
                        $body = $event->getEventBody();
                        logger()->warning('Unknown event. [' . get_class($event) . ']', compact('body'));
                }
            }
            return response()->json(200);
        } catch (Exception $e) {
            Log::debug('--------------------------');
            Log::debug(json_encode($e->getMessage()));
            Log::debug('--------------------------');
            return response()->json(500);
        }
    }

    /**
     * @param $event
     * @param \App\Models\LineMessage $line_message
     * @return mixed|string
     */
    function createNewRichmenu($event, LineMessage $line_message)
    {
        // すでにメニューがあるか
        $line_rich_menus_model = LineRichMenu::where('vendor_id', $line_message->vendor_id)->first();
        Log::debug($line_rich_menus_model);
        if ($line_rich_menus_model) {
            // すでにあればのIDをセット
            $rich_menu_id = $line_rich_menus_model->id;
        } else {
            // すでに登録されているメニュー数
            $menu_list = $this->getListOfRichmenu($line_message->channel_access_token);
            if (count($menu_list['richmenus']) == 0) {
                // 新規リッチID取得
                $rich_menu_id = $this->createCurlNewRichmenu($line_message);
                $new_line_rich_menus = new LineRichMenu();
                $new_line_rich_menus->id = $rich_menu_id;
                $new_line_rich_menus->vendor_id = $line_message->vendor_id;
                $new_line_rich_menus->user_id = $event->getUserId();
                $new_line_rich_menus->save();
            } else {
                $rich_menu_id = $menu_list['richmenus'][0]['richMenuId'];
            }
        }
        Log::debug($rich_menu_id);
        // リッチメニュー画像をセットする
        $upload_image_result = $this->uploadRandomImageToRichmenu($line_message->channel_access_token, $rich_menu_id);
        Log::debug($upload_image_result);
        // 会員にセット
        $user_mach_result = $this->setUserAllRichmenu($line_message->channel_access_token, $rich_menu_id);
        Log::debug($user_mach_result);
        return $user_mach_result;
    }

    /**
     * リッチメニュー取得
     * @param $channelAccessToken
     * @return mixed
     */
    function getListOfRichmenu($channelAccessToken)
    {
        $sh = <<< EOF
  curl \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/richmenu/list;
EOF;
        return json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
    }

    /**
     * リッチメニューを登録
     * @param \App\Models\LineMessage $line_message
     * @return mixed
     */
    function createCurlNewRichmenu(LineMessage $line_message)
    {
        if ($line_message->line_uri1) {
            $sh = <<< EOF
  curl -X POST \
  -H 'Authorization: Bearer $line_message->channel_access_token' \
  -H 'Content-Type:application/json' \
  -d '{
    "size": {
        "width": 2500,
        "height": 1686
    },
    "selected": true,
    "name": "いい感じ",
    "chatBarText": "メニュー",
    "areas": [
        {
            "bounds": {
                "x": 0,
                "y": 0,
                "width": 843,
                "height": 843 
            },
            "action": {
                "type": "message",
                "text": "トレーナー予約"
            }
        },
        {
            "bounds": {
                "x": 843,
                "y": 0,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "message",
                "text": "予約確認"
            }
        },
        {
            "bounds": {
                "x": 1686,
                "y": 0,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "uri",
                "uri": "##menu_url##"
            }
        },
        {
            "bounds": {
                "x": 0,
                "y": 843,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "uri",
                "uri": "##store_url##"
            }
        },
        {
            "bounds": {
                "x": 843,
                "y": 843,
                "width": 840,
                "height": 843
            },
            "action": {
                "type": "uri",
                "uri": "##profile_url##"
            }
        },
        {
            "bounds": {
                "x": 1686,
                "y": 843,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "uri",
                "uri": "##line_uri1##"
            }
        }
    ]
}' https://api.line.me/v2/bot/richmenu;
EOF;
            // ラインURI
            if ($line_message->line_uri1) {
                $sh = str_replace('##line_uri1##', "https://line.me/R/ti/p/" . $line_message->line_uri1, $sh);
            }
        } else {
            $sh = <<< EOF
  curl -X POST \
  -H 'Authorization: Bearer $line_message->channel_access_token' \
  -H 'Content-Type:application/json' \
  -d '{
    "size": {
        "width": 2500,
        "height": 1686
    },
    "selected": true,
    "name": "いい感じ",
    "chatBarText": "メニュー",
    "areas": [
        {
            "bounds": {
                "x": 0,
                "y": 0,
                "width": 843,
                "height": 843 
            },
            "action": {
                "type": "message",
                "text": "トレーナー予約"
            }
        },
        {
            "bounds": {
                "x": 843,
                "y": 0,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "message",
                "text": "予約確認"
            }
        },
        {
            "bounds": {
                "x": 1686,
                "y": 0,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "uri",
                "uri": "##menu_url##"
            }
        },
        {
            "bounds": {
                "x": 0,
                "y": 843,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "uri",
                "uri": "##store_url##"
            }
        },
        {
            "bounds": {
                "x": 843,
                "y": 843,
                "width": 840,
                "height": 843
            },
            "action": {
                "type": "uri",
                "uri": "##profile_url##"
            }
        },
        {
            "bounds": {
                "x": 1686,
                "y": 843,
                "width": 843,
                "height": 843
            },
            "action": {
                "type": "message",
                "text": "お問い合わせは現在利用できません"
            }
        }
    ]
}' https://api.line.me/v2/bot/richmenu;
EOF;
        }
        // メニュー
        $sh = str_replace('##menu_url##', url(route('home')), $sh);
        // URL置換
        $sh = str_replace('##uri##', url(route('home')), $sh);
        // 店舗情報
        $sh = str_replace('##store_url##', $line_message->store_url, $sh);
        // メニュー
        $sh = str_replace('##profile_url##', url(route('user.edit')), $sh);
        Log::debug($sh);
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        if (isset($result['richMenuId'])) {
            return $result['richMenuId'];
        } else {
            return $result['message'];
        }
    }

    /**
     * 画像をアップロード
     * @param $channelAccessToken
     * @param $richmenuId
     * @return mixed|string
     */
    function uploadRandomImageToRichmenu($channelAccessToken, $richmenuId)
    {
        if (!$this->isRichmenuIdValid($richmenuId)) {
            return 'invalid richmenu id';
        }
        $randomImageIndex = rand(1, 5);
        // $imagePath = realpath('') . '/' . 'controller_0' . $randomImageIndex . '.png';
        // $imagePath = public_path('storage/images/' . 'controller_0' . $randomImageIndex . '.png');
        $imagePath = storage_path('app/public/assets/images/menu-01.jpg');
        if (!File::exists($imagePath)) {
            return 'Not file.';
        }
        $sh = <<< EOF
  curl -X POST \
  -H 'Authorization: Bearer $channelAccessToken' \
  -H 'Content-Type: image/png' \
  -H 'Expect:' \
  -T $imagePath \
  https://api-data.line.me/v2/bot/richmenu/$richmenuId/content
EOF;
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        if (isset($result['message'])) {
            return $result['message'];
        } else {
            return 'success. Image #0' . $randomImageIndex . ' has uploaded onto ' . $richmenuId;
        }
    }

    /***
     * @param $string
     * @return bool
     */
    function isRichmenuIdValid($string): bool
    {
        if (preg_match('/^[a-zA-Z0-9-]+$/', $string)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * デフォルトリッチメニューセット
     * @param $channelAccessToken
     * @param $richmenuId
     * @return mixed|string
     */
    function setUserAllRichmenu($channelAccessToken, $richmenuId)
    {
        if (!$this->isRichmenuIdValid($richmenuId)) {
            return 'invalid richmenu id';
        }
        $sh = <<< EOF
  curl -X POST \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/user/all/richmenu/$richmenuId
EOF;
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        if (isset($result['message'])) {
            return $result['message'];
        } else {
            return 'success';
        }
    }

    /**
     * @return string
     */
    private function followEventMessage(): string
    {
        $app_title_ja = getenv('APP_TITLE_JA');
        return <<< EOF
お友達参加ありがとうございます。
{$app_title_ja}運営局です。

まずはメニューの「プロフィール」をタップしてプロフィールを登録してください。^^
EOF;
    }

    /**
     * @param $bot
     * @param $reply_token
     */
    public function profileCheck($bot, $reply_token)
    {
        $actions = [
            new UriTemplateActionBuilder("お客様情報設定",
                url(route('user.edit')),
            ),
//            new UriTemplateActionBuilder("管理画面（一時的）",
//                config('app.url') . 'admin'
//            ),
        ];
        $button = new ButtonTemplateBuilder('お客様情報未設定', '利用するには必ずお客様情報を設定してください', null, $actions);
        $msg = new TemplateMessageBuilder('Finish generate playlist', $button);
        $bot->replyMessage($reply_token, $msg);
    }

    /**
     * @param \App\Models\Admin $trainer
     * @return \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder
     */
    private function createTrainerCarousel(Admin $trainer): CarouselColumnTemplateBuilder
    {
//        $text = str_limit($trainer->self_introduction, 2, '');
        // カルーセルに付与するボタンを作る
        $action = new UriTemplateActionBuilder(
            "予約する",
            url(route('channel.trainer.reservation.show', [$trainer->vendor_id, $trainer->id])));// config('app.url') . 'trainer/' . $trainer->id);
        // カルーセルのカラムを作成する
        $action2 = new UriTemplateActionBuilder(
            "プロフィール",
            url(route('channel.trainer.show', [$trainer->vendor_id, $trainer->id])));
        return new CarouselColumnTemplateBuilder(
            $trainer->name,
            str_limit($trainer->self_introduction, 30, ''),
            $trainer->profileImage->getPhotoUrl(), [$action, $action2]);
//            $trainer->profileImage->getPhotoUrl(), [$action]);
    }

    /**
     * @param $channelAccessToken
     * @param $richmenuId
     * @return mixed|string
     */
    function deleteRichmenu($channelAccessToken, $richmenuId)
    {
        if (!$this->isRichmenuIdValid($richmenuId)) {
            return 'invalid richmenu id';
        }
        $sh = <<< EOF
  curl -X DELETE \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/richmenu/$richmenuId
EOF;
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        return $result['message'] ?? 'success';
    }

    /**
     * @param $bot
     * @param $reply_token
     */
    public function adminUrl($bot, $reply_token)
    {
        $actions = [
            new UriTemplateActionBuilder("管理画面へ",
                url(route('admin.login')),
            ),
//            new UriTemplateActionBuilder("管理画面（一時的）",
//                config('app.url') . 'admin'
//            ),
        ];
        $button = new ButtonTemplateBuilder('管理画面', 'トレーナー専用管理画面', null, $actions);
        $msg = new TemplateMessageBuilder('Finish generate playlist', $button);
        $bot->replyMessage($reply_token, $msg);
    }

    /**
     * メニューをセット
     * @param $event
     * @param \App\Models\LineMessage $line_message
     */
    function setRichmenu($event, LineMessage $line_message)
    {
        // すでにメニューがあるか
        $line_rich_menus = LineRichMenu::where('vendor_id', $line_message->vendor_id)->first();
        if ($line_rich_menus) {
            // リッチメニュー画像をセットする
            $upload_image_result = $this->uploadRandomImageToRichmenu($line_message->channel_access_token, $line_rich_menus->id);
            // 会員にセット
            $user_mach_result = $this->setUserAllRichmenu($line_message->channel_access_token, $line_rich_menus->id);
        }
    }

    /**
     * @param $bot
     * @param $event
     * @return array
     */
    public function reservationConfirmation($bot, $event): array
    {
        $data = [];
        $now = Carbon::now();
        $reservations = Reservation::where('id', $event->getUserId())->where('reserved_at', '>=', $now)->get();
        if ($reservations) {
            foreach ($reservations as $key => $reservation) {
                $data[$key]['reservation_start'] = $reservation->reservation_start;
            }
        }

        return $data;
    }

    /**
     * 設定されているリッチメニューを確認する
     * @param $channelAccessToken
     * @param $userId
     * @return mixed
     */
    function checkRichmenuOfUser($channelAccessToken, $userId)
    {
        $sh = <<< EOF
  curl \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/user/$userId/richmenu
EOF;
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        if (isset($result['richMenuId'])) {
            return $result['richMenuId'];
        } else {
            return $result['message'];
        }
    }

    /**
     * @param $channelAccessToken
     * @param $userId
     * @return mixed|string
     */
    function unlinkFromUser($channelAccessToken, $userId)
    {
        $sh = <<< EOF
  curl -X DELETE \
  -H 'Authorization: Bearer $channelAccessToken' \
  https://api.line.me/v2/bot/user/$userId/richmenu
EOF;
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        if (isset($result['message'])) {
            return $result['message'];
        } else {
            return 'success';
        }
    }

    /**
     * @param $channelAccessToken
     * @param $userId
     * @param $richmenuId
     * @return mixed|string
     */
    function linkToUser($channelAccessToken, $userId, $richmenuId)
    {
        if (!$this->isRichmenuIdValid($richmenuId)) {
            return 'invalid richmenu id';
        }
        $sh = <<< EOF
  curl -X POST \
  -H 'Authorization: Bearer $channelAccessToken' \
  -H 'Content-Length: 0' \
  https://api.line.me/v2/bot/user/$userId/richmenu/$richmenuId
EOF;
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        if (isset($result['message'])) {
            return $result['message'];
        } else {
            return 'success';
        }
    }

    /**
     * 会員とリッチメニューを紐付け
     * @param $channelAccessToken
     * @param $userId
     * @param $richmenuId
     * @return mixed|string
     */
    function setUserRichmenu($channelAccessToken, $userId, $richmenuId)
    {
        if (!$this->isRichmenuIdValid($richmenuId)) {
            return 'invalid richmenu id';
        }
        $sh = <<< EOF
  curl -X POST \
  -H 'Authorization: Bearer $channelAccessToken' \
  -H 'Content-Length: 0' \
  https://api.line.me/v2/bot/user/$userId/richmenu/$richmenuId
EOF;
        $result = json_decode(shell_exec(str_replace('\\', '', str_replace(PHP_EOL, '', $sh))), true);
        if (isset($result['message'])) {
            return $result['message'];
        } else {
            return 'success';
        }
    }
}
