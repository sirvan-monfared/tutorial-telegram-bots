<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StartController;

$router->exact('/start', StartController::class, 'handle');
$router->exact('/products', ProductsController::class, 'index');

$router->exact('/cart', CartController::class, 'index');
$router->startsWith('/cart/add/', CartController::class, 'store');
$router->startsWith('/cart/delete/', CartController::class, 'destroy');

