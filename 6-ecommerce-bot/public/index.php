<?php

use App\Core\Authenticator;
use App\Core\BotRouter;
use App\Core\Session;
use App\Exceptions\RouteNotMatchException;
use App\Services\TelegramService;
use App\Services\UserService;

error_reporting(E_ALL);
ini_set('display_errors', '1');
//ini_set("display_errors", 0);

const BASE_PATH = __DIR__ . '/../';
const SITE_URL = 'https://ikimsbzgkg.sharedwithexpose.com';

require BASE_PATH . "vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

require base_path('bootstrap.php');

$telegram = new TelegramService();
$user = UserService::findOrCreateUser($telegram->chatId());
$router = new BotRouter($telegram, $user);

require(base_path('routes/routes.php'));


$router->match();

