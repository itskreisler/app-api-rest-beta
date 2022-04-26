<?php

namespace App\Controllers\Backend;

use Core\Authenticate;
use Core\Helpers;
use Jajo\JSONDB;
use Core\Log;


class WelcomeControllers
{
    use Authenticate;

    public function index()
    {
        echo Helpers::jsonencode($_SESSION);
    }
    public function register($request)
    {
        $RUTA_APP = RUTA_APP;
        $typefile = 'users';
        $json_db = new JSONDB("{$RUTA_APP}\\DB");
        $users = $json_db->select()
            ->from($typefile)
            ->where(['username' => $request->get->username])
            ->get();
        $count = count($users);
        if ($count == 0) {
            $json_db->insert(
                $typefile,
                [
                    'id' => $request->get->id,
                    'username' => $request->get->username,
                    'password' => password_hash($request->get->username, PASSWORD_DEFAULT)
                ]
            );
        } else {
            $json_db->update([
                'username' => $request->get->username,

            ])
                ->from($typefile)
                ->where(['id' => $request->get->id, 'username' => $request->get->username])
                ->trigger();
        }
        /*$db = new SqlModels();*/
        /*$insert = $db->insert('users', ['name' => 'nombre', 'phone' => '5']);*/
        /*$update = $db->update('users', ['name' => 'nombre'], ['id' => 6]);*/
        /*$delete = $db->delete('users', ['id' => 14]);*/
        /*$users  = SqlModels::getRows('users', ['order_by' => 'id ASC']);*/
        /*jsonencode($users);*/
        $users2 = $json_db->select()
            ->from($typefile)
            ->get();
        echo Helpers::jsonencode(["message" => "registro exitoso"]);
    }

    public function login($request)
    {


        $au = $this->auth($request);

        echo Helpers::jsonencode($au);
    }

    public function _logout()
    {

        $this->logout();
    }
}
