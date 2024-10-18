<?php

namespace App\Services;

use Telegram;

class TelegramService
{
    protected Telegram $provider;

    protected ?int $chat_id = null;
    protected ?string $text = null;
    protected ?array $data = null;

    const string KEYBOARD_BUTTON_CALLBACK = 'callback';
    const string KEYBOARD_BUTTON_URL = 'url';

    const string KEYBOARD_STYLE_INLINE = 'inline';
    const string KEYBOARD_STYLE_FULL = 'full';

    public function __construct($use_proxy = true, ?int $chat_id = null)
    {
        $proxy_data = $use_proxy ? ['url' => env('PROXY_URL'), 'port' => env('PROXY_PORT')] : [];

        $this->provider = new Telegram(env('TELEGRAM_BOT_TOKEN'), proxy: $proxy_data);

        $this->chat_id = $chat_id ?? $this->provider->ChatID();
        $this->text = $this->provider->Text();
        $this->data = $this->provider->getData();
    }

    public function sendMessage(
        string $text,
        ?array $keyboard = null,
        $keyboard_buttons_type = self::KEYBOARD_BUTTON_CALLBACK,
        $keyboard_style = self::KEYBOARD_STYLE_INLINE,
        ?int $chat_id = null,
        bool $remove_keyboard = false
    )
    {

        if ($remove_keyboard) {
            $keyboard = $this->provider->buildKeyBoardHide();
        } elseif ($keyboard) {
            $keyboard = $this->prepareKeyboard($keyboard, $keyboard_buttons_type, $keyboard_style);
        }

        return $this->provider->sendMessage([
            'chat_id' => $chat_id ?? $this->chatId(),
            'reply_markup' => $keyboard,
            'text' => $text
        ]);
    }

    public function editMessage(
        string $text,
        ?array $keyboard = null,
        mixed $message_to_edit = null,
        $keyboard_buttons_type = self::KEYBOARD_BUTTON_CALLBACK,
        $keyboard_style = self::KEYBOARD_STYLE_INLINE,
        bool $remove_keyboard = false
    )
    {

        $message_id = $this->provider->MessageID();
        if (is_array($message_to_edit)) {
            $message_id = $message_to_edit['result']['message_id'];
        }
        if (is_numeric($message_to_edit)) {
            $message_id = (int) $message_to_edit;
        }

        if ($remove_keyboard) {
            $keyboard = $this->provider->buildKeyBoardHide();
        } elseif ($keyboard) {
            $keyboard = $this->prepareKeyboard($keyboard, $keyboard_buttons_type, $keyboard_style);
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

    protected function prepareKeyboard(array $structure, $buttons_type, $keyboard_style)
    {
        $buttons = [];

        foreach ($structure as $keyboardRow) {
            $row = [];

            foreach ($keyboardRow as $title => $action) {
                $button_url = $buttons_type === self::KEYBOARD_BUTTON_URL ? $action : null;
                $button_callback = $buttons_type === self::KEYBOARD_BUTTON_CALLBACK ? $action : null;
                $row[] = $this->buildKeyboardButton($keyboard_style, $title, $button_url, $button_callback);
            }

            $buttons[] = $row;
        }

        return $this->buildKeyboard($keyboard_style, $buttons);
    }

    private function buildKeyboardButton($keyboard_style, $title, $button_url, $button_callback): array
    {
        if ($keyboard_style === self::KEYBOARD_STYLE_INLINE) {
            return $this->provider->buildInlineKeyboardButton($title, $button_url, $button_callback);
        }

        return $this->provider->buildKeyboardButton($title);
    }

    private function buildKeyboard(string $style, array $buttons): false|string
    {
        if ($style === self::KEYBOARD_STYLE_INLINE) {
            return $this->provider->buildInlineKeyBoard($buttons);
        }

        return $this->provider->buildKeyBoard($buttons);
    }
}
