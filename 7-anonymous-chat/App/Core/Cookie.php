<?php

namespace App\Core;

class Cookie
{
    public static function get($name)
    {
        return self::has($name) ? $_COOKIE[$name] : null;
    }

    public static function has($name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public static function set($name, $value, $expires_at): void
    {
        $params = session_get_cookie_params();

        setcookie($name, $value, $expires_at, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    public static function delete($name): void
    {
        $expires_at = time() - 24 * 60 * 60;

        self::set($name, '', $expires_at);
    }
}