<?php


namespace Core;


class Auth
{
    private static $id = null;
    private static $username = null;

    public function __construct()
    {

        if (Session::get('datos')) {
            $datos = Session::get('datos');
            self::$id = $datos['id'];
            self::$username = $datos['username'];
        }

    }


    public static function check()
    {
        if (self::$id == null || self::$username == null)
            return false;
        return true;
    }
}