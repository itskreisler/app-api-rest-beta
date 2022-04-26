<?php


namespace Core;


class Container
{
    public static function newController($contoller)
    {
        if (class_exists("App\\Controllers\\" . $contoller)) {
            $objContoller = "App\\Controllers\\" . $contoller;
            return new $objContoller;
        } else {
            return false;
        }
    }

}