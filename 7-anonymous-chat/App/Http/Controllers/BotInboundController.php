<?php

namespace App\Http\Controllers;

use App\Core\BotRouter;
use App\Services\TelegramService;
use App\Services\UserService;

class BotInboundController extends BotBaseController
{
    public function inbound(): void
    {
        $telegram = new TelegramService();

        if (! $telegram->chatId()) {
            die('please go back!');
        }

        $user = UserService::findOrCreateUser($telegram->chatId());
        $bot_router = new BotRouter($telegram, $user);

        require(base_path('routes/bot_routes.php'));

        $bot_router->match();
    }
}