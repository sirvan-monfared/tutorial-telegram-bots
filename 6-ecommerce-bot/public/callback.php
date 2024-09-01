<?php

use App\Exceptions\GatewayException;
use App\Models\Order;
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



} catch (GatewayException $e) {
    die($e->getMessage());
}


dd($order);