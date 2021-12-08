<?php
namespace App\Core;

use App\Controllers\Controller_Main;

class Route
{
    static function start()
    {
        $controller_name = 'Main';
        $action_name = 'index';
        $url_components = parse_url($_SERVER['REQUEST_URI']);
        parse_str($url_components['query'], $params);
        $GLOBALS['GET_PARAMS'] = $params;
        $_SERVER['REQUEST_URI'] = strtok($_SERVER['REQUEST_URI'], '?');
        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if ( !empty($routes[1]) )
        {
            $controller_name = $routes[1];
        }

        if ( !empty($routes[2]) )
        {
            $action_name = $routes[2];
        }

        // $model_name = 'Model_'.$controller_name;
        $controller_name = 'Controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        $files1 = scandir("app/models/");
        foreach ($files1 as $model_file) {
            if (!in_array($model_file ,[".", ".."]))
                if(file_exists("app/models/".$model_file))
                    include "app/models/".$model_file;
        }

        $services = scandir("app/services/");
        foreach ($services as $service_file) {
            if (!in_array($service_file ,[".", ".."]))
                if(file_exists("app/services/".$service_file))
                    include "app/services/".$service_file;
        }

        $controller_file = strtolower($controller_name).'.php';
        $controller_path = "app/controllers/".$controller_file;
        if(file_exists($controller_path))
        {
            include "app/controllers/".$controller_file;
        }
        else
        {
            Route::ErrorPage404();
        }

        $controller_name = "App\\Controllers\\" . $controller_name;
        $controller = new $controller_name;
        $action = $action_name;

        if(method_exists($controller, $action))
        {
            $controller->$action();
        }
        else
        {
            Route::ErrorPage404();
        }

    }

    function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404');
    }
}