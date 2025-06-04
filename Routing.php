<?php
class Routing {
    public static $getRoutes = [];
    public static $postRoutes = [];

    public static function get($url, $controller){
        self::$getRoutes[$url] = $controller;
    }

    public static function post($url, $controller) {
        self::$postRoutes[$url] = $controller;
    }

    public static function run($url) {
        $action = explode("/", $url)[0] ?: "index";
        $method = $_SERVER['REQUEST_METHOD'];

        error_log("Routing uruchomiony dla: $action");
        error_log("Metoda żądania: $method");

        if ($method === 'POST') {
            if (!array_key_exists($action, self::$postRoutes)) {
                die("POST: brak ścieżki dla '$action'");
            }
            $controllerName = self::$postRoutes[$action];
        } else {
            if (!array_key_exists($action, self::$getRoutes)) {
                die("GET: brak ścieżki dla '$action'");
            }
            $controllerName = self::$getRoutes[$action];
        }

        error_log("Kontroler: $controllerName");

        $controller = new $controllerName;

        if (!method_exists($controller, $action)) {
            die("Metoda $action nie istnieje w $controllerName");
        }

        $controller->$action();
    }
}
