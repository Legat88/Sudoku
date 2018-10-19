<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 10.10.2018
 * Time: 9:39
 */

class Router
{
    private const PATH_TO_CONTROLLERS = 'app/controllers/';
    private const PATH_TO_MODELS = 'app/models/';

    public static function start()
    {
        $controller_name = 'Main';
        $action_name = 'index';


        $model_name = $controller_name . 'Model';
        $controller_name = $controller_name . 'PageController';

        $model_file = $model_name . '.php';
        $model_path = self::PATH_TO_MODELS . $model_file;

        $controller_file = $controller_name . '.php';
        $controller_path = self::PATH_TO_CONTROLLERS . $controller_file;

        $path = $_SERVER['REQUEST_URI'];
        if ($path == '/' || $path == '/home' || $path == '/main' || $path == '/sudoku') {
            if (file_exists($model_path)) {
                include $model_path;
            }
            if (file_exists($controller_path)) {
                include $controller_path;
            }
        } elseif ($path == '/NotFoundPage') {
            $controller_name = 'NotFoundPageController';
            $controller_file = $controller_name . '.php';
            $controller_path = self::PATH_TO_CONTROLLERS . $controller_file;
            if (file_exists($controller_path)) {
                include $controller_path;
            }
        } else {
            Router::Error404();
        }

        if (!class_exists($controller_name)) {
            Router::Error404();
        } else {
            $controller = new $controller_name;
            $action = $action_name;

            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                Router::Error404();
            }
        }

    }

    public static function Error404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . 'NotFoundPage');
    }
}