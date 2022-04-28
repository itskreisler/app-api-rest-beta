<?php
use Core\Routing;

Routing::get('/',"Backend\\WelcomeControllers@index");
Routing::get('/register',"Backend\\WelcomeControllers@register");
Routing::get('/login',"Backend\\WelcomeControllers@login");
Routing::get('/logout',"Backend\\WelcomeControllers@_logout");
