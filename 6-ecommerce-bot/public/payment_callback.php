<?php

use App\Exceptions\GatewayException;
use App\Models\Order;
use App\Services\TelegramService;
use App\Services\ZibalGateway;

require_once 'init.php';

$data = $_GET;

if (intval($data['success']) !== 1 || intval($data['status']) !== 2) {
    die('payment failed');
}

$order = (new Order())->byTrackId($data['trackId']);


if (! $order) {
    die('payment not valid');
}

try {
    $gateway = (new ZibalGateway)->verify($order->track_id);

    $order->makePaid($gateway->supportId());

    $order->cart()->close();

    $telegram = new TelegramService(use_proxy: true, chat_id: $order->user()->chat_id);
    $telegram->sendMessage("پرداخت شما با موفقیت انجام شد");

    die('پرداخت با موفقیت انجام شد. برای ادامه کار به ربات مراجعه کنید');
} catch (GatewayException $e) {
    die($e->getMessage());
}


