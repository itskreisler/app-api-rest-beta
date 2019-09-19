<?php
date_default_timezone_set("America/Bogota");
//setlocale(LC_TIME, "es_CO");
if (!session_id()) session_start();
require_once __DIR__.'/Helpers.php';
require_once __DIR__.'/Redirect.php';
require_once __DIR__.'/Session.php';
require_once __DIR__."/../app/config/config.php";
require_once __DIR__.'/DataBase.php';
require_once __DIR__."/../routes/Routes.php";
new \Core\Route($route);


