<?php

namespace App\Services\Line;

use App\Models\ChannelJoin;
use App\Models\LineMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\Event\UnfollowEvent;
use Throwable;

class UnFollowService
{
    private $bot;
    private LineMessage $line_message;

    /**
     * FollowService constructor.
     * @param LINEBot $bot
     */
    public function __construct(LINEBot $bot, LineMessage $line_message)
    {
        $this->bot = $bot;
        $this->line_message = $line_message;
    }

    /**
     * @param \LINE\LINEBot\Event\UnfollowEvent $event
     * @return bool
     */
    public function execute(UnfollowEvent $event): bool
    {
        DB::beginTransaction();
        try {
            $user = User::withTrashed()->where('id', $event->getUserId())->first();
            $user->delete();
            $channel_join = ChannelJoin::withTrashed()->where('user_id', $event->getUserId())->where('vendor_id', $this->line_message->vendor_id)->first();
            $channel_join->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::debug(json_encode($e->getMessage()));
            return false;
        }
    }
}
