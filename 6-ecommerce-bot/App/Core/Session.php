<?php

namespace App\Core;

class Session {

    public static function has($key): bool
    {
        return !empty(Session::get($key));
    }

    public static function put($key, $value) 
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        return $_SESSION['_flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function flash($key, $value) 
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public static function unflash()
    {
        unset($_SESSION['_flash']);
    }

    public static function flush(): void
    {
        $_SESSION = [];
    }

    public static function unset($key): void
    {
        if (Session::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy(): void
    {
        self::flush();
    
        session_destroy();

        Cookie::delete('PHPSESSID');
    }

    public static function success($text = 'عملیات با موفقیت انجام شد'): void
    {
        Session::flash('_success', $text);
    }

    public static function warning($text = 'لطفا خطاهای ذکرشده را اصلاح کنید'): void
    {
        Session::flash('_warning', $text);
    }

}