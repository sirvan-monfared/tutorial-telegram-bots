<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StartController;

$router->exact('/start', StartController::class, 'handle');
$router->exact('/products', ProductsController::class, 'index');

$router->exact('/cart', CartController::class, 'index');
$router->startsWith('/cart/add/', CartController::class, 'store');
$router->startsWith('/cart/delete/', CartController::class, 'destroy');

$router->exact('/profile', ProfileController::class, 'index');
$router->exact('/profile/name', ProfileController::class, 'editName');
$router->after('/profile/name', ProfileController::class, 'updateName');
$router->exact('/profile/address', ProfileController::class, 'editAddress');
$router->after('/profile/address', ProfileController::class, 'updateAddress');

