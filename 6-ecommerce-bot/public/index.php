<?php

use App\Core\BotRouter;
use App\Services\TelegramService;
use App\Services\UserService;

require_once 'init.php';

$telegram = new TelegramService();

if (! $telegram->chatId()) {
    die('please go back!');
}

$user = UserService::findOrCreateUser($telegram->chatId());
$router = new BotRouter($telegram, $user);

require(base_path('routes/routes.php'));


$router->match();

