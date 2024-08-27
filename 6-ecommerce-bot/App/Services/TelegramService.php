<?php

namespace App\Services;

use Telegram;

class TelegramService
{
    protected Telegram $provider;

    protected int $chat_id;
    protected string $text;
    protected array $data;

    public function __construct($use_proxy = true)
    {
        $proxy_data = $use_proxy ? ['url' => env('PROXY_URL'), 'port' => env('PROXY_PORT')] : [];

        $this->provider = new Telegram(env('TELEGRAM_BOT_TOKEN'), proxy: $proxy_data);

        $this->chat_id = $this->provider->ChatID();
        $this->text = $this->provider->Text();
        $this->data = $this->provider->getData();
    }

    public function sendMessage(string $text, ?array $keyboard = null)
    {

        if ($keyboard) {
            $keyboard = $this->prepareKeyboard($keyboard);
        }


        return $this->provider->sendMessage([
            'chat_id' => $this->chatId(),
            'reply_markup' => $keyboard,
            'text' => $text
        ]);
    }

    public function editMessage(string $text, ?array $keyboard = null, mixed $message_to_edit = null)
    {

        $message_id = $this->provider->MessageID();
        if (is_array($message_to_edit)) {
            $message_id = $message_to_edit['result']['message_id'];
        }
        if (is_numeric($message_to_edit)) {
            $message_id = (int) $message_to_edit;
        }

        if ($keyboard) {
            $keyboard = $this->prepareKeyboard($keyboard);
        }

        return $this->provider->editMessageText([
            'chat_id' => $this->chatId(),
            'reply_markup' => $keyboard,
            'text' => $text,
            'message_id' => $message_id
        ]);
    }


    public function chatId()
    {
        return $this->chat_id;
    }

    public function text()
    {
        return $this->text;
    }

    public function data()
    {
        return $this->data;
    }

    public function provider()
    {
        return $this->provider;
    }

    protected function prepareKeyboard(array $structure)
    {
        $buttons = [];

        foreach ($structure as $keyboardRow) {
            $row = [];

            foreach ($keyboardRow as $title => $action) {
                $row[] = $this->provider->buildInlineKeyboardButton($title, callback_data: $action);
            }

            $buttons[] = $row;
        }

        return $this->provider->buildInlineKeyBoard($buttons);
    }
}
