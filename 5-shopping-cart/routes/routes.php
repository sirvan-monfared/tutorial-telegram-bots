<?php

use App\Http\Controllers\StartController;

$router->exact('/start', StartController::class, 'handle');


//$router->post('/', [TelegramController::class, 'inbound']);
