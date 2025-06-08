<?php
class Routing {
    public static $getRoutes = [];
    public static $postRoutes = [];

    public static function get($url, $controller, $method = null){
        self::$getRoutes[$url] = ['controller' => $controller, 'method' => $method];
    }

    public static function post($url, $controller, $method = null) {
        self::$postRoutes[$url] = ['controller' => $controller, 'method' => $method];
    }

    public static function run($url) {
        $action = explode("/", $url)[0] ?: "index";
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($requestMethod === 'POST') {
            if (!isset(self::$postRoutes[$action])) {
                die("POST: brak ścieżki dla '$action'");
            }
            $route = self::$postRoutes[$action];
        } else {
            if (!isset(self::$getRoutes[$action])) {
                die("GET: brak ścieżki dla '$action'");
            }
            $route = self::$getRoutes[$action];
        }

        $controllerName = $route['controller'];
        $methodName     = $route['method'] ?? $action;

        $controller = new $controllerName;
        if (!method_exists($controller, $methodName)) {
            die("Metoda $methodName nie istnieje w $controllerName");
        }
        $controller->$methodName();
    }
}
