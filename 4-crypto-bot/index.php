<?php

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

    $cryptoList = array_slice($result, 0, 10);

    $buttons = [["دلار" => "/currency/dollar", "سکه" => "/currency/coin"]];

    foreach (array_chunk($cryptoList, 2) as $cryptoGroup) {
        $buttons[] = [
            $cryptoGroup[0]->name => "/crypto/{$cryptoGroup[0]->key}",
            $cryptoGroup[1]->name => "/crypto/{$cryptoGroup[1]->key}"
        ];
    }

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
    // save command to database
    $telegram->sendMessage("لطفا بخشی از نام رمزارز مدنظر خود را وارد کنید");
    return;
}

if (! empty($telegram->text())) {

    // 1. check user's last command
    // 2. if last command equals to /search  then search for entered parameter
    // 3. else send error



    $telegram->sendMessage("دستور وارد شده معتبر نیست ❌");
    return;
}
