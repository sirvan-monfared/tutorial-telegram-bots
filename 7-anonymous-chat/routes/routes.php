<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BotInboundController;
use App\Http\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index'], 'home');


$router->post('/inbound', [BotInboundController::class, 'inbound'], 'bot.inbound');

require BASE_PATH . 'routes/admin.php';
