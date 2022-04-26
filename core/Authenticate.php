<?php

namespace Core;

use App\Models\UserModels;

trait Authenticate
{
    public function login()
    {
        $this->setPageTitle('Login');
        return $this->renderView('login/login');
    }

    public function auth($request)
    {
        $data = [];
        $result = UserModels::where($request);

        if ($result && password_verify($request->get->password, $result['password'])) {
            $datos = [
                'id' => $result['id'],
                'username' => $result['username'],
                'password' => $result['password']
            ];
            Session::set('datos', $datos);
            $data['message'] = ["Credenciales correctas"];
        } else {
            $data['message'] = ["Credenciales incorrectas"];
        }
        return $data;
    }

    public function logout()
    {
        Session::destroy('datos');
        echo Helpers::jsonencode(["message" => "Sesion cerrada"]);
        //return Redirect::route('/');
    }
}
