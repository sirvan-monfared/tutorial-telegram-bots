<?php

use App\Core\Authenticator;
use App\Core\BotRouter;
use App\Core\Session;
use App\Exceptions\RouteNotMatchException;
use App\Services\TelegramService;

error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set("display_errors", 0);

const BASE_PATH = __DIR__ . '/../';
const SITE_URL = 'https://6ae7-5-106-210-126.ngrok-free.app/';

require BASE_PATH . "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

require base_path('bootstrap.php');

$telegram = new TelegramService();
$router = new BotRouter($telegram);

require(base_path('routes/routes.php'));


$router->match();

