<?php

class Router {
    protected $routes = [];

    public function add($route, $controller, $action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($url) {
        $url = trim($url, '/');

        foreach ($this->routes as $route => $params) {
            $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);

            if (preg_match('#^' . $route . '$#', $url, $matches)) {
                $controller = $params['controller'];
                $action = $params['action'];

                require_once '../app/controllers/' . $controller . '.php';
                $controller = new $controller();

                $args = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                call_user_func_array([$controller, $action], $args);
                return;
            }
        }

        // Handle 404 Not Found
        echo "404 Not Found";
    }
}