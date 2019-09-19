<?php

namespace Core;


class Cookie
{
    public static function set($key, $value, $expirar = 3600)
    {
        return setcookie($key, $value, (time() + $expirar));
    }

    public static function get($key)
    {
        if (isset($_COOKIE[$key]))
            return $_COOKIE[$key];
        return false;
    }

    public static function destroy($keys, $expirar = 1)
    {
        /*if (is_array($keys))
            foreach ($keys as $key)
                unset($_COOKIE[$key]);
        unset($_COOKIE[$keys]);*/
        return setcookie($keys, "", (time() - $expirar));
    }
}