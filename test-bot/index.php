<?php
//  https://api.telegram.org/bot7365316974:AAHHgOLvpvAdCdhTCk5IsJgF4WTgtRyqLds/setWebhook?url=https://59ac-178-131-154-29.ngrok-free.app/test-bot/


function sendMessage($chat_id, $text)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.telegram.org/bot7365316974:AAHHgOLvpvAdCdhTCk5IsJgF4WTgtRyqLds/sendMessage',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('chat_id' => $chat_id, 'text' => $text),
        CURLOPT_PROXY => '127.0.0.1',
        CURLOPT_PROXYPORT => '12334',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response);
}


$_POST = json_decode(file_get_contents('php://input'), true);

$chat_id = $_POST['message']['chat']['id'];
$text = $_POST['message']['text'];


if ($text === '/start') {
    sendMessage($chat_id, "سلام . به ربات من خوش آمدی");
    return;
}

if ($text === '/name') {
    sendMessage($chat_id, "نام این ربات لاراپلاس است");
    return;
}

sendMessage($chat_id, "لطفا یک دستور معتبر وارد نمایید");
