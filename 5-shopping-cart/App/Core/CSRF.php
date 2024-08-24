<?php

namespace App\Core;

class CSRF
{
    public static function exceptionRoutes(): array
    {
        return [
            route('checkout.callback')
        ];
    }

    public static function token(): string
    {
        if (Session::has('csrf')) {
            return Session::get('csrf');
        }

        $token = generateRandom(25);
        Session::put('csrf', $token);

        return $token;
    }

    public static function verify(): bool
    {
        if (self::isInExceptionRoutes()) {
            return true;
        }

        $session = Session::get('csrf');
        Session::unset('csrf');

        return !empty($session) && $_POST['_token'] === $session;
    }

    private static function isInExceptionRoutes(): bool
    {
        return in_array(currentUrl(), self::exceptionRoutes());
    }
}