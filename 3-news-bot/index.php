<?php
//  https://api.telegram.org/bot7225095237:AAEhrmI9i0LY2g7d-IV8Yl35wP9eHByL6pg/setWebhook?url=https://137b-178-131-155-45.ngrok-free.app/4-crypto-bot/
require_once 'Telegram.php';
require_once 'functions.php';


$telegram = new Telegram('7502866540:AAGWCx5m0qCWz3zsZcmj0VEZuzwCkh6Pk90', proxy: [
    'url' => '127.0.0.1',
    'port' => '12334'
]);


$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$data = $telegram->getData();

if ($text === '/start') {

    $keyboard = $telegram->buildInlineKeyBoard([
        [$telegram->buildInlineKeyboardButton('لیست خبرگزاری ها', callback_data: '/home')]
    ]);

    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "به ربات آخرین خبر خوش آمدید. برای دیدن لیست خبرگزاری ها روی دکمه زیر کلیک کنید 👌",
    ]);
}


if ($text === '/home') {

    $buttons = [
        [
            $telegram->buildInlineKeyboardButton('شبکه خبر', callback_data: '/irinn'),
            $telegram->buildInlineKeyboardButton('خبرگزاری تسنیم ', callback_data: '/tasnim')
        ],
        [
            $telegram->buildInlineKeyboardButton('خبرگزاری مهر', callback_data: '/mehr'),
            $telegram->buildInlineKeyboardButton('خبرگزاری ایرنا ', callback_data: '/irna')
        ],
        [
            $telegram->buildInlineKeyboardButton('خبرگزاری میزان', callback_data: '/mizan'),
            $telegram->buildInlineKeyboardButton('ورزش 3', callback_data: '/varzesh3')
        ]
    ];

    $keyboard = $telegram->buildInlineKeyBoard($buttons);


    $telegram->editMessageText([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "به ربات آخرین خبر خوش آمدید. طفا یکی از خبرگزاری ها را انتخاب کنید 👌",
        'message_id' => $telegram->MessageID()
    ]);
}


$availableSources = ['/irinn', '/tasnim', '/mehr', '/irna', '/mizan', '/varzesh3'];


if (in_array($text, $availableSources)) {

    $source = ltrim($text, '/');
    $response = request($source);

    $output = $response->title . "\n\n";
    $output .= $response->link . "\n\n";

    $output .= " ----------------------- \n\n";


    foreach (array_slice($response->item, 0, 5) as $item) {
        $output .= "- {$item->title} \n\n";
        $output .= $item->link . "\n\n";
    }

    $keyboard = $telegram->buildInlineKeyBoard([
        [$telegram->buildInlineKeyboardButton('بازگشت', callback_data: '/home')]
    ]);


    $telegram->editMessageText([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $output,
        'message_id' => $telegram->MessageID()
    ]);
}
