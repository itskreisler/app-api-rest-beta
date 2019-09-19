<?php


namespace App\Models;


class UserModels
{
    private static $table = 'users';
    public static function all(){
        return SqlModels::getRows(self::$table);
    }
}