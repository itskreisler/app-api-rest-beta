<?php
namespace App\Controllers\Backend;

use Core\Helpers;

class WelcomeControllers
{
    public function index($request)
    {
        $RUTA_APP = RUTA_APP;
        /*$db = new SqlModels();*/
        /*$insert = $db->insert('users', ['name' => 'nombre', 'phone' => '5']);*/
        /*$update = $db->update('users', ['name' => 'nombre'], ['id' => 6]);*/
        /*$delete = $db->delete('users', ['id' => 14]);*/
        /*$users  = SqlModels::getRows('users', ['order_by' => 'id ASC']);*/
        /*jsonencode($users);*/
        echo Helpers::jsonencode($_GET);
    }
}