<?php

namespace App\Services\Line;

use App\Models\LineMessage;
use App\Models\LineTextMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Flysystem\Exception;
use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Throwable;

class ReceiveTextService
{
    private $bot;
    private LineMessage $line_message;

    /**
     * ReceiveTextService constructor.
     * @param \LINE\LINEBot $bot
     * @param \App\Models\LineMessage $line_message
     */
    public function __construct(LINEBot $bot, LineMessage $line_message)
    {
        $this->bot = $bot;
        $this->line_message = $line_message;
    }

    /**
     * @param \LINE\LINEBot\Event\MessageEvent\TextMessage $event
     * @return bool
     */
    public function execute(TextMessage $event): bool
    {
        DB::beginTransaction();
        try {
            $line_id = $event->getUserId();
            if (!$line_id) {
                throw new Exception('トークンが見つかりません');
            }
            $new_message = new LineTextMessage();
            $new_message->vendor_id = $this->line_message->vendor_id;
            $new_message->user_id = $line_id;
            $new_message->message = $event->getText();
            $new_message->save();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            Log::debug($e->getMessage());
            DB::rollBack();
            return false;
        }
    }
}
