<?php

namespace App\Core\Middlewares;

class Auth {

    public function handle()
    {
        if (! $_SESSION['user'] ?? false) {
            redirectTo(route('auth.login'));
        }
    }

}