<?php


namespace Core;


class Auth
{
    private static $id = null;
    private static $email = null;
    private static $status = null;
    private static $code = null;
    private static $rol = null;
    private static $name = null;

    public function __construct()
    {

        if (Session::get('datos')) {
            $datos = Session::get('datos');
            self::$id = $datos['uId'];
            self::$email = $datos['pEmail'];
            self::$status = $datos['stId'];
            self::$code = $datos['pCode'];
            self::$rol = $datos['roId'];
            self::$name = $datos['pName'];
        }

    }

    public static function id()
    {
        return self::$id;
    }

    public static function name()
    {
        return self::$name;
    }

    public static function email()
    {
        return self::$email;
    }

    public static function status()
    {
        return self::$status;
    }

    public static function rol()
    {
        return self::$rol;
    }

    public static function code()
    {
        return self::$code;
    }

    public static function check()
    {
        if (self::$id == null || self::$name == null || self::$email == null || self::$status == null || self::$code == null || self::$rol == null)
            return false;
        return true;
    }
}