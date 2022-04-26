<?php


namespace App\Models;

use Jajo\JSONDB;

class UserModels
{
    static $table = 'users';
    
    
    public static function all()
    {
        
    }
    public static function where($request)
    {
        $RUTA_APP = RUTA_APP;
        $file=self::$table;
        $json_db = new JSONDB("{$RUTA_APP}\\DB");
        $users = $json_db->select()
            ->from($file)
            ->where(['username' => $request->get->username])
            ->get();
        $count = count($users);
        return ($count == 0) ? [] : $users[0];
    }
}
