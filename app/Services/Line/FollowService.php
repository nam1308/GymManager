<?php

namespace App\Services\Line;

use App\Models\ChannelJoin;
use App\Models\LineMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\Event\FollowEvent;

class FollowService
{
    private $bot;
    private LineMessage $line_message;

    public function __construct(LINEBot $bot, LineMessage $line_message)
    {
        $this->bot = $bot;
        $this->line_message = $line_message;
    }

    /**
     * @param \LINE\LINEBot\Event\FollowEvent $event
     * @return bool
     */
    public function execute(FollowEvent $event): bool
    {
        try {
            $rsp = $this->bot->getProfile($event->getUserId());
            if (!$rsp->isSucceeded()) {
                return false;
            }
            /////////////////////////////////////
            /// プロフィール取得
            $profile = $rsp->getJSONDecodedBody();
            Log::debug(json_encode($profile));
            return DB::transaction(function () use ($profile) {
                // 会員が登録されているか？
                $user = User::withTrashed()->where('id', $profile['userId'])->first();
                Log::debug('1');
                if (!$user) {
                    ///////////////////////////////////
                    /// 会員登録
                    $new_user = new User();
                    $new_user->id = $profile['userId'];
                    $new_user->display_name = $profile['displayName'];
                    $new_user->picture_url = $profile['pictureUrl'];
                    $new_user->save();
                    Log::debug('2');
                } else {
                    $user->restore();
                    Log::debug('3');
                }
                // チャンネル登録されているか
                $channel_join = ChannelJoin::withTrashed()->where('user_id', $profile['userId'])->where('vendor_id', $this->line_message->vendor_id)->first();
                if (!$channel_join) {
                    Log::debug('4');
                    ///////////////////////////////////
                    /// チャンネルに属する
                    $new_channel_join = new ChannelJoin();
                    $new_channel_join->vendor_id = $this->line_message->vendor_id;
                    $new_channel_join->user_id = $profile['userId'];
                    $new_channel_join->save();
                } else {
                    Log::debug('5');
                    $channel_join->restore();
                }
//                $user = User::withTrashed()->where('id', $profile['userId'])->first();
//                Log::debug(json_encode($user));
//                if (!$user) {
//                    ///////////////////////////////////
//                    /// 会員登録
//                    $new_user = new User();
//                    $new_user->id = $profile['userId'];
//                    $new_user->display_name = $profile['displayName'];
//                    $new_user->picture_url = $profile['pictureUrl'];
//                    $new_user->save();
//                    ///////////////////////////////////
//                    /// チャンネルに属する
//                    $new_channel_join = new ChannelJoin();
//                    $new_channel_join->vendor_id = $this->line_message->vendor_id;
//                    $new_channel_join->user_id = $profile['userId'];
//                    $new_channel_join->save();
//                } else {
//                    $channel_join = ChannelJoin::withTrashed()->where('user_id', $profile['userId'])->where('vendor_id', $this->line_message->vendor_id)->first();
//                    if (is_null($user->deleted_at)) {
//                        $user->delete();
//                        $channel_join->delete();
//                    } else {
//                        $user->restore();
//                        $channel_join->restore();
//                    }
//                }
                return true;
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            return false;
        }
    }
}
