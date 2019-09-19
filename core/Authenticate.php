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
        if ($request->post->roId == 3){
            $user = [
                "suEmail" => $request->post->suEmail
            ];
            $result = UserModels::wheres($user);
            if ($result && password_verify($request->post->password, $result->uPassword)){
                $datos = [
                    'uId' => $result->uId,
                    'pEmail' => $result->pEmail,
                    'pName' => $result->suNames,
                    'pCode' => $result->pCode,
                    'roId' => $result->roId,
                    'stId' => $result->stId,
                ];
                Session::set('datos', $datos);
                return Redirect::route('/');

            }
            return Redirect::route('/', [
                'errors' => ['El e-mail o la contraseña esta incorrecta'],
                'inputs' => ['pEmail' => $request->post->pEmail]
            ]);

        }elseif ($request->post->roId == 1 || $request->post->roId == 2){
            $user = [
                "suEmail" => $request->post->suEmail
            ];
            $result = UserModels::where($user);
            //var_dump($result);
            //var_dump($request->post);
            if ($result && password_verify($request->post->password, $result->uPassword)){
                $datos = [
                    'uId' => $result->uId,
                    'pEmail' => $result->pEmail,
                    'pName' => $result->pName,
                    'pCode' => $result->pCode,
                    'roId' => $result->roId,
                    'stId' => $result->stId,
                ];
                Session::set('datos', $datos);
                return Redirect::route('/');

            }
            return Redirect::route('/', [
                'errors' => ['El e-mail o la contraseña esta incorrecta'],
                'inputs' => ['pEmail' => $request->post->pEmail]
            ]);
        }elseif ($request->post->roId == 4){
            $user = [
                "suEmail" => $request->post->suEmail
            ];
            $result = UserModels::wherecoffe($user);
            //print_html($result);
            //var_dump($request->post);
            if ($result && password_verify($request->post->password, $result->uPassword)){
                $datos = [
                    'uId' => $result->uId,
                    'pEmail' => $result->pEmail,
                    'pName' => $result->csName,
                    'pCode' => $result->pCode,
                    'roId' => $result->roId,
                    'stId' => $result->stId,
                ];
                Session::set('datos', $datos);
                return Redirect::route('/');

            }
            return Redirect::route('/', [
                'errors' => ['El e-mail o la contraseña esta incorrecta'],
                'inputs' => ['pEmail' => $request->post->pEmail]
            ]);
        }elseif ($request->post->roId == 0){
            return Redirect::route('/', [
                'errors' => ['El e-mail o la contraseña esta incorrecta'],
                'inputs' => ['pEmail' => $request->post->pEmail]
            ]);
        }

    }

    public function logout()
    {
        Session::destroy('datos');
        return Redirect::route('/');
    }


}