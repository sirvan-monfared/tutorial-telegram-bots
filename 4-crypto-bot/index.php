<?php

use App\Services\Database;
use App\Services\HttpRequest;
use App\Services\TelegramService;

require 'vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$telegram  = new TelegramService();


if ($telegram->text() === '/start') {

    $keyboard = [
        ['مشاهده لیست ارزها' => '/home'],
    ];

    $telegram->sendMessage("برای شروع روی لینک زیر کلیک کنید", $keyboard);
    return;
}

if ($telegram->text() === '/home') {
    $loading_message = $telegram->sendMessage('در حال دریافت اطلاعات ...');

    $result = (new HttpRequest)->cryptolist();


    $currency_buttons = [["دلار" => "/currency/dollar", "سکه" => "/currency/coin"]];
    $buttons = organizeCryptoButtonsAsTwoColumn($result, $currency_buttons);
    $buttons[] = ["جستجو" => "/search"];


    $telegram->editMessage("لطفا روی یکی از گزینه های زیر کلیک کنید", $buttons, $loading_message);
    return;
}

if (str_starts_with($telegram->text(), '/currency/')) {
    $key = str_replace('/currency/', '', $telegram->text());

    if ($key !== 'dollar' && $key !== 'coin') {
        $telegram->sendMessage("دستور وارد شده معتبر نیست ❌");

        return;
    }

    $loading_message = $telegram->sendMessage('در حال دریافت اطلاعات ...');

    $list = (new HttpRequest)->currencyList();

    if ($key === 'dollar') {
        $text = "قیمت روز دلار: " . $list->currencies->dollar->p . "\n\n";
        $text .= "بالاترین قیمت روز دلار: " . $list->currencies->dollar->h . "\n\n";
        $text .= "پایین ترین قیمت روز دلار: " . $list->currencies->dollar->l . "\n\n";
    }

    if ($key === 'coin') {
        $text = "قیمت روز سکه گرمی: " . $list->coin->gerami->p . "\n\n";
        $text .= " قیمت روز سکه ربع: " . $list->coin->rob->p . "\n\n";
        $text .= "  قیمت نیم سکه  " . $list->coin->nim->p . "\n\n";
        $text .= "  قیمت سکه تمام  " . $list->coin->sekee->p . "\n\n";
    }


    $keyboard = [
        ['بازگشت' => '/home']
    ];

    $telegram->editMessage($text, $keyboard, $loading_message);
    return;
}

if (str_starts_with($telegram->text(), '/crypto/')) {
    $crypto_key = str_replace('/crypto/', '', $telegram->text());


    $loading_message = $telegram->sendMessage('در حال دریافت اطلاعات ...');

    $list = (new HttpRequest)->cryptolist();

    $result = array_filter($list, fn($crypto) => $crypto->key === $crypto_key);
    $crypto = array_shift($result);

    $text = "نام: {$crypto->name} \n\n";
    $text .= "نام اصلی: {$crypto->name_en} \n\n";
    $text .= " قیمت: " . number_format($crypto->price) . " دلار \n\n";


    $keyboard = [
        ['بازگشت' => '/home']
    ];

    $telegram->editMessage($text, $keyboard, $loading_message);
    return;
}

if ($telegram->text() === '/search') {

    $sql = "INSERT INTO `commands` (`chat_id`, `command`, `created_at`) VALUES (:chat_id,:command,:created_at)";

    $values = [
        'chat_id' => $telegram->chatId(),
        'command' => '/search',
        'created_at' => now()
    ];

    (new Database)->prepare($sql, $values);

    $telegram->sendMessage("لطفا بخشی از نام رمزارز مدنظر خود را وارد کنید");
    return;
}

if (! empty($telegram->text())) {


    $record = findLatestActiveCommand($telegram->chatId());


    if (! $record || $record['command'] !== '/search') {
        $telegram->sendMessage("دستور وارد شده معتبر نیست ❌");
        return;
    }

    $loading_message = $telegram->sendMessage('در حال دریافت اطلاعات ...');


    $list = (new HttpRequest)->cryptolist();

    $search_text = $telegram->text();
    $cryptos = array_filter($list, function ($item) use ($search_text) {
        return  str_contains($item->name, $search_text) ||
            str_contains($item->name_en, $search_text) ||
            str_contains($item->key, $search_text);
    });


    $buttons = organizeCryptoButtonsAsTwoColumn($cryptos);

    $buttons[] = ["بازگشت" => "/home"];

    markCommandAsCompleted($record['id']);


    $telegram->editMessage("لطفا روی یکی از گزینه های زیر کلیک کنید", $buttons, $loading_message);
    return;
}


function findLatestActiveCommand($chat_id)
{
    $sql = "SELECT * FROM `commands` WHERE `chat_id`=:chat_id AND `completed`=0 ORDER BY `id` DESC LIMIT 1";

    return (new Database)->prepare($sql, [
        'chat_id' => $chat_id
    ])->find();
}
function markCommandAsCompleted($command_id)
{
    $sql = "UPDATE `commands` SET `completed`=1 WHERE `id`=:id";
    (new Database)->prepare($sql, [
        'id' => $command_id
    ]);
}


function organizeCryptoButtonsAsTwoColumn(array $cryptoList, ?array $buttons = [], ?int $limit = 10)
{
    $cryptoList = array_slice($cryptoList, 0, $limit);

    foreach (array_chunk($cryptoList, 2) as $cryptoGroup) {
        $buttons[] = [
            $cryptoGroup[0]->name => "/crypto/{$cryptoGroup[0]->key}",
            $cryptoGroup[1]->name => "/crypto/{$cryptoGroup[1]->key}"
        ];
    }

    return $buttons;
}
