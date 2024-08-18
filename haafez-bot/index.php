<?php
//  https://api.telegram.org/bot7469858148:AAGN6F4WidbO9KoweFjAnmqTjhrtEw7m-Vo/setWebhook?url=https://13f2-178-131-152-128.ngrok-free.app/news-bot/
require_once 'Telegram.php';


$telegram = new Telegram('7469858148:AAGN6F4WidbO9KoweFjAnmqTjhrtEw7m-Vo', proxy: [
    'url' => '127.0.0.1',
    'port' => '12334'
]);


$chat_id = $telegram->ChatID();
$text = $telegram->Text();
$data = $telegram->getData();

if ($text === '/start') {


    // $buttons = [
    //     [$telegram->buildKeyboardButton('/omen'), $telegram->buildKeyboardButton('/info')],
    // ];

    // $keyboard = $telegram->buildKeyboard($buttons);

    $buttons = [
        [$telegram->buildInlineKeyboardButton('بیا فالت بگیرم! 👍', callback_data: '/omen'), $telegram->buildInlineKeyboardButton('اطلاعات ربات ℹ️', callback_data: '/info')]
    ];

    $keyboard = $telegram->buildInlineKeyBoard($buttons);


    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => 'سلام به ربات فال حافظ خوش آمدید. برای گرفتن فال دستور /omen رو وارد کنید'
    ]);
}

if ($text === '/info') {
    $output = "آیدی چت: " . $chat_id . "\n\n";
    $output .= "نام ربات: " . $data['message']['chat']['first_name'] . "\n\n";
    $output .= "نام کاربری: " . $data['message']['chat']['username'] . "\n\n";

    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => $output
    ]);
}

if ($text === '/omen') {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://one-api.ir/rss/?token=131896%3A66904c8243926&action=tasnim',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response);


    var_dump($response);

    $omen = $response->result;

    $output = "متن فال شما: \n\n";
    $output .= $omen->RHYME . "\n\n";

    $output .= "معنای فال شما: \n\n";
    $output .= $omen->MEANING;

    $keyboard = $telegram->buildKeyBoardHide();

    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => $output
    ]);
}
