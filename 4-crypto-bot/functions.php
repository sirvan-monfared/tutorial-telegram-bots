<?php

if (! function_exists('env')) {
    function env($key)
    {
        return $_ENV[$key];
    }
}


if (! function_exists('now')) {
    function now($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }
}
