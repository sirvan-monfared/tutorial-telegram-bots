<?php

namespace App\Services;

use Telegram;

class TelegramService
{
    protected Telegram $provider;

    protected ?int $chat_id = null;
    protected ?string $text = null;
    protected ?array $data = null;

    const KEYBOARD_BUTTON_CALLBACK = 'callback';
    const KEYBOARD_BUTTON_URL = 'url';

    public function __construct($use_proxy = true, ?int $chat_id = null)
    {
        $proxy_data = $use_proxy ? ['url' => env('PROXY_URL'), 'port' => env('PROXY_PORT')] : [];

        $this->provider = new Telegram(env('TELEGRAM_BOT_TOKEN'), proxy: $proxy_data);

        $this->chat_id = $chat_id ?? $this->provider->ChatID();
        $this->text = $this->provider->Text();
        $this->data = $this->provider->getData();
    }

    public function sendMessage(string $text, ?array $keyboard = null, $keyboard_buttons_type = self::KEYBOARD_BUTTON_CALLBACK)
    {

        if ($keyboard) {
            $keyboard = $this->prepareKeyboard($keyboard, $keyboard_buttons_type);
        }

        return $this->provider->sendMessage([
            'chat_id' => $this->chatId(),
            'reply_markup' => $keyboard,
            'text' => $text
        ]);
    }

    public function editMessage(string $text, ?array $keyboard = null, mixed $message_to_edit = null, $keyboard_buttons_type = self::KEYBOARD_BUTTON_CALLBACK)
    {

        $message_id = $this->provider->MessageID();
        if (is_array($message_to_edit)) {
            $message_id = $message_to_edit['result']['message_id'];
        }
        if (is_numeric($message_to_edit)) {
            $message_id = (int) $message_to_edit;
        }

        if ($keyboard) {
            $keyboard = $this->prepareKeyboard($keyboard, $keyboard_buttons_type);
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

    public function provider(): Telegram
    {
        return $this->provider;
    }

    protected function prepareKeyboard(array $structure, $buttons_type)
    {
        $buttons = [];

        foreach ($structure as $keyboardRow) {
            $row = [];

            foreach ($keyboardRow as $title => $action) {
                $button_url = $buttons_type === self::KEYBOARD_BUTTON_URL ? $action : null;
                $button_callback = $buttons_type === self::KEYBOARD_BUTTON_CALLBACK ? $action : null;
                $row[] = $this->provider->buildInlineKeyboardButton($title, $button_url, $button_callback);
            }

            $buttons[] = $row;
        }

        return $this->provider->buildInlineKeyBoard($buttons);
    }
}
