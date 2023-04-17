<?php

namespace App\Services\Line;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;

class ReceiveLocationService
{
    private $bot;

    /**
     * ReceiveLocationService constructor.
     * @param LINEBot $bot
     */
    public function __construct(LineBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * @param \LINE\LINEBot\Event\MessageEvent\LocationMessage $event
     * @return bool
     */
    public function execute(LocationMessage $event): bool
    {
        return true;
    }
}
