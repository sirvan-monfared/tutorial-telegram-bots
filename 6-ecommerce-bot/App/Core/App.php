<?php

namespace App\Core;

class App
{
    private static Container $container;

    public static function setContainer(Container $container): void
    {
        static::$container = $container;
    }

    public static function container(): Container
    {
        return static::$container;
    }

    public static function resolve($key)
    {
        return static::$container->resolve($key);
    }
}

