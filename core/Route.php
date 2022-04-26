<?php

namespace Core;

use stdClass;
use Core\Auth;
class Route
{
    private $routes;

    public function __construct()
    {
        $this->setRoutes(Routing::getall());
        $this->run();
    }

    private function setRoutes($routes)
    {
        $newRoutes=[];
        foreach ($routes as $route) {
            $explode = explode('@', $route[1]);
            if (isset($route[2])) {
                $r = [$route[0], $explode[0], $explode[1], $route[2], $route[3]];
            } else {
                $r = [$route[0], $explode[0], $explode[1], $explode[3]];
            }
            $newRoutes[] = $r;
        }
        $this->routes = $newRoutes;
    }

    private function run()
    {
        $url = strtolower($this->getUrl());
        $urlArray = explode('/', $url);
        $param = [];
        $controller = null;
        $action = null;
        foreach ($this->routes as $route) {
            $routeArray = explode('/', $route[0]);
            $param = [];
            for ($i = 0; $i < count($routeArray); $i++) {
                if ((strpos($routeArray[$i], "{") !== false) && (count($urlArray) == count($routeArray))) {
                    $routeArray[$i] = $urlArray[$i];
                    $param[] = $urlArray[$i];
                }
                $route[0] = implode('/', $routeArray);
            }
            $param[] = $this->getRequest();
            if ($url == $route[0] && $_SERVER['REQUEST_METHOD'] == $route[4] && !$route[3]) {
                $found = true;
                $controller = $route[1];
                $action = $route[2];
                break;
            }else if($url == $route[0] && $_SERVER['REQUEST_METHOD'] == $route[4] && (new Auth())->check()){
                $found = true;
                $controller = $route[1];
                $action = $route[2];
                break;
            }
        }

        if (isset($found)) {
            $controller = Container::newController($controller);
            call_user_func_array([$controller, $action], $param);

        } else {
            echo Helpers::jsonencode([
                'code' => '404',
                'status' => 'error',
                'message' => 'No tienes permisos para ver esta ruta',
                'url'=> $url
            ]);
        }
    }

    private function getUrl()
    {
        if (strlen(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) >= 2) {
            if (substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), -1) == '/') {
                return substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 0, -1);
            } else {
                return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            }
        } else {
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }
    }

    private function getRequest()
    {
        $obj = new stdClass;
        $get = new stdClass;
        $post = new stdClass;

        foreach ($_GET as $key => $value) {
            @$get->$key = $value;
        }
        $obj->get = $get;
        foreach ($_POST as $key => $value) {
            @$post->$key = $value;
        }
        $obj->post = $post;
        return $obj;
    }

}
