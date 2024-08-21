<?php

require 'vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$telegram = new Telegram($_ENV['TELEGRAM_BOT_TOKEN'], proxy: [
    'url' => '127.0.0.1',
    'port' => '12334'
]);

$text = $telegram->Text();
$chat_id = $telegram->ChatId();
$data = $telegram->getData();

if ($text === '/start') {

    $keyboard = $telegram->buildInlineKeyBoard([
        [$telegram->buildInlineKeyboardButton('مشاهده لیست ارزها', callback_data: "/home")]
    ]);

    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "برای شروع روی لینک زیر کلیک کنید",
    ]);
}

if ($text === '/home') {

    $loading_message = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'در حال دریافت اطلاعات ...'
    ]);

    $result = getCryptoList();

    $cryptoList = array_slice($result, 0, 10);

    $buttons = [];
    foreach ($cryptoList as $crypto) {
        $buttons[] = [$telegram->buildInlineKeyboardButton($crypto->name, callback_data: "/crypto/{$crypto->key}")];
    }

    $keyboard = $telegram->buildInlineKeyBoard($buttons);

    $telegram->editMessageText([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "لطفا روی یکی از گزینه های زیر کلیک کنید",
        'message_id' => $loading_message['result']['message_id']
    ]);

    // $telegram->editMessageText([
    //     'chat_id' => $chat_id,
    //     'reply_markup' => $keyboard,
    //     'text' => "لطفا روی یکی از گزینه های زیر کلیک کنید",
    //     'message_id' => $telegram->MessageID(),
    // ]);

    // $telegram->deleteMessage([
    //     'chat_id' => $chat_id,
    //     'message_id' => $loading_message['result']['message_id']
    // ]);
}

if (str_starts_with($text, '/crypto/')) {
    $crypto_key = str_replace('/crypto/', '', $text);


    $loading_message = $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'در حال دریافت اطلاعات ...'
    ]);

    $list = getCryptoList();

    $result = array_filter($list, fn($crypto) => $crypto->key === $crypto_key);
    $crypto = array_shift($result);

    $text = "نام: {$crypto->name} \n\n";
    $text .= "نام اصلی: {$crypto->name_en} \n\n";
    $text .= " قیمت: " . number_format($crypto->price) . " دلار \n\n";


    $keyboard = $telegram->buildInlineKeyBoard([
        [$telegram->buildInlineKeyboardButton('بازگشت', callback_data: "/home")]
    ]);

    $telegram->editMessageText([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'message_id' => $loading_message['result']['message_id'],
        'text' => $text,
    ]);

    // $telegram->editMessageText([
    //     'chat_id' => $chat_id,
    //     'reply_markup' => $keyboard,
    //     'message_id' => $telegram->MessageID(),
    //     'text' => $text,
    // ]);

    // $telegram->deleteMessage([
    //     'chat_id' => $chat_id,
    //     'message_id' => $loading_message['result']['message_id']
    // ]);
}
