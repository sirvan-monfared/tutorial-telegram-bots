<?php

if (! function_exists('env')) {
    function env($key)
    {
        return $_ENV[$key];
    }
}
