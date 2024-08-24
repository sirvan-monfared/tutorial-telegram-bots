<?php

namespace App\Core\Middlewares;

class Guest {

    public function handle()
    {
        if ($_SESSION['user'] ?? false) {
            redirectTo('/');
        }
    }

}