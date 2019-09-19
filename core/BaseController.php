<?php


namespace Core;


abstract class BaseController
{
    protected $view;
    protected $views = array();
    protected $auth;
    protected $errors;
    protected $inputs;
    protected $success;
    private $viewPath;
    private $layoutPath;
    private $datos = [];
    private $pageTitle = null;
    private $pageSubTitle = null;

    public function __construct()
    {
        $this->view = new \stdClass;
        $this->views = new \stdClass;
        $this->auth = new Auth;
    }

    public function forbiden()
    {
        return Redirect::route('/');
    }

    protected function renderView($viewPath, $layoutPath = null, $datos = [])
    {
        $this->viewPath = $viewPath;
        $this->layoutPath = $layoutPath;
        $this->datos = $datos;
        if ($layoutPath) {
            return $this->layout();
        } else {
            return $this->content();
        }

    }

    protected function layout()
    {
        if (file_exists(__DIR__ . "/../app/Views/{$this->layoutPath}.php")) {
            require_once __DIR__ . "/../app/Views/{$this->layoutPath}.php";
        } else {
            echo "Error: ruta de diseño no encontrada!";
        }
    }

    protected function content()
    {
        if (file_exists(__DIR__ . "/../app/Views/{$this->viewPath}.php")) {
            require_once __DIR__ . "/../app/Views/{$this->viewPath}.php";
        } else {
            echo "Error: ruta de diseño no encontrada!";
        }
    }

    protected function getView($view, $key = null, $value = null, $ext = ".php")
    {
        if ($view != "") {
            $view = explode("/", $view);
            $route = "";
            for ($i = 0; $i < count($view); $i++) {
                if ($i == count($view) - 1) {
                    $route .= $view[$i] . $ext;
                } else {
                    $route .= $view[$i] . "/";
                }
            }
            if (file_exists(__DIR__ . '../app/Views/' . $route)) {
                if (!is_null($key)) {
                    if (is_array($key)) {
                        extract($key, EXTR_PREFIX_SAME, "");
                    } else {
                        ${$key} = $value;
                    }
                }
                require_once __DIR__ . '/../app/Views/' . $route;
            } else {
                die("No existe la vista");
            }

        }
        return null;
    }

    protected function getPageTitle($separator = null)
    {
        if ($separator) {
            return $this->pageTitle . " " . $separator . " ";
        } else {
            return $this->pageTitle;
        }
    }

    protected function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    protected function getPageSubTitle($separator = null)
    {
        if ($separator) {
            return $this->pageSubTitle . " " . $separator . " ";
        } else {
            return $this->pageSubTitle;
        }
    }

    protected function setPageSubTitle($pageSubTitle)
    {
        $this->pageSubTitle = $pageSubTitle;
    }
}