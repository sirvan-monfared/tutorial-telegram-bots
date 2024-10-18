<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;

$router->get('/admin', [AdminController::class, 'index'], 'admin.index');

$router->resource('/admin/user', UserController::class, 'admin.user');