<?php

use App\Core\Authenticator;
use App\Core\Session;
use App\Core\Router;
use App\Helpers\Cart;

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//ini_set("display_errors", 0);

const BASE_PATH = __DIR__ . '/../';
const SITE_URL = 'http://7-anonymous-chat.test/';

require BASE_PATH . "vendor/autoload.php";

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$url = currentUrl();

require base_path('bootstrap.php');

(new Authenticator())->check();

$router = new Router();

require(base_path('routes/routes.php'));

try {
    $router->match(httpRequestMethod());
} catch (\App\Exceptions\RouteNotMatchException $e) {
    abort();
}


Session::unflash();
