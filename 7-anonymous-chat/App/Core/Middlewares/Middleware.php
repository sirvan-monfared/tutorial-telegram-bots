<?php

namespace App\Core\Middlewares;

class Middleware {

    const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
        'admin' => Admin::class
    ];


    public static function handle($key) 
    {
        if (! $key) {
            return;
        }
        
        $middleware = self::MAP[$key];
        if (! $middleware) {
            return;
        }

        (new $middleware)->handle();
    }
}