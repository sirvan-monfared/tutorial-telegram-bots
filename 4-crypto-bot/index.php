<?php

require 'vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$telegram = new Telegram($_ENV['TELEGRAM_BOT_TOKEN'], proxy: [
    'url' => '127.0.0.1',
    'port' => '12334'
]);

echo $telegram->ChatID();
