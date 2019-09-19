<?php

namespace App\Controllers;
use App\Models\SqlModels;
use Core\BaseController;
class WelcomeControllers extends BaseController
{
    public function index()
    {
        /*$db = new SqlModels();*/
        /*$insert = $db->insert('users', ['name' => 'nombre', 'phone' => '5']);*/
        /*$update = $db->update('users', ['name' => 'nombre'], ['id' => 6]);*/
        /*$delete = $db->delete('users', ['id' => 14]);*/
        /*$users  = SqlModels::getRows('users', ['order_by' => 'id ASC']);*/
        /*jsonencode($users);*/
        echo "hola";
    }
}