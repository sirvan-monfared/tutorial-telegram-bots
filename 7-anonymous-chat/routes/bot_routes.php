<?php

use App\Http\Controllers\Bot\ChatController;
use App\Http\Controllers\Bot\ConversationController;
use App\Http\Controllers\Bot\StartController;

$bot_router->exact('/start', StartController::class, 'index');

$bot_router->exact('/chat/random', ChatController::class, 'random');

$bot_router->exact('لغو چت🚫', ConversationController::class, 'stop');
$bot_router->default(ConversationController::class, 'chat');