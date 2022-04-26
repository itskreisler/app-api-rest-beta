<?php


namespace Core;


class Routing
{
    private static $routes;

    public static function get($route, $controller_funcion, $auth = false)
    {
        self::$routes[] = [$route, $controller_funcion, $auth, 'GET'];
        return self::$routes;
    }

    public static function post($route, $controller_funcion, $auth = false)
    {
        self::$routes[] = [$route, $controller_funcion, $auth, 'POST'];
        return self::$routes;
    }

    public static function put($route, $controller_funcion, $auth = false)
    {
        self::$routes[] = [$route, $controller_funcion, $auth, 'UPDATE'];
        return self::$routes;
    }

    public static function delete($route, $controller_funcion, $auth = false)
    {
        self::$routes[] = [$route, $controller_funcion, $auth, 'DELETE'];
        return self::$routes;
    }

    public static function getall()
    {
        return self::$routes;
    }
}