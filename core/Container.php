<?php


namespace Core;


class Container
{
    public static function newController($contoller)
    {
        $objContoller = "App\\Controllers\\" . $contoller;
        return new $objContoller;
    }

    public static function getModel($model)
    {
        $objModel = "\\App\\Models\\" . $model;
        return new $objModel(DataBase());
    }

    public static function pageNotFound()
    {
        if (file_exists(__DIR__ . '/../app/Views/404.php')) {
            require_once __DIR__ . '/../app/Views/404.php';
        } else {
            echo "¡Error 404 - Página no encontrada!";
        }
    }

}