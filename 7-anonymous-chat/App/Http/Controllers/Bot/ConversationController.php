<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\BotBaseController;
use App\Models\Chat;
use App\Models\Conversation;
use App\Models\User;
use App\Services\TelegramService;

class ConversationController extends BotBaseController
{
    public function chat($user_command)
    {
        $chat = $this->user->ongoingChat();

        if (! $chat) {
            return $this->telegram->sendMessage("❌دستور وارد شده معتبر نیست❌");
        }

        $sender = $this->user;
        $receiver = ($this->user->id === $chat->host) ? $chat->guest() : $chat->host();

        $chat->addMessage(
            content: $user_command,
            sender: $sender->id,
            receiver: $receiver->id
        );

        $keyboard = [
            ['لغو چت🚫' => '']
        ];

        $this->telegram->sendMessage($user_command, $keyboard, keyboard_style: TelegramService::KEYBOARD_STYLE_FULL, chat_id: $receiver->chat_id);
    }

    public function stop()
    {
        $chat = $this->user->ongoingChat();

        $chat->close();

        $this->user->deleteLatestRequest();

        $this->telegram->sendMessage("🚫 چت لغو شد", chat_id: $chat->host()->chat_id, remove_keyboard: true);
        $this->telegram->sendMessage("🚫 چت لغو شد", chat_id: $chat->guest()->chat_id, remove_keyboard: true);
    }
}