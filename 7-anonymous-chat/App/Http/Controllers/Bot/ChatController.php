<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\BotBaseController;
use App\Models\Chat;
use App\Models\Request;

class ChatController extends BotBaseController
{
    public function random()
    {
        // check if a request exists
        $request = $this->user->findOrCreateRequest();

        $random = (new Request)->randomChat($this->user->id);

        if (! $random) {
            return $this->telegram->sendMessage("❌ کاربری برای چت تصادفی یافت نشد ... لطفا منتظر بمانید ❌");
        }

        $host = $this->user;
        $guest = $random->user();

        // create a chat
        $chat = (new Chat())->start(host: $host->id, guest: $guest->id);

        $this->telegram->sendMessage("چت شما با یک کاربر ناشناس آغاز شد");
        $this->telegram->sendMessage("چت شما با یک کاربر ناشناس آغاز شد", chat_id: $guest->chat_id);
    }
}