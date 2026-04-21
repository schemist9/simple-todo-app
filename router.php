<?php

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

class Router
{
    private $routes = [];
    
    public function __construct()
    {
        $this->routes = [];
    }

    public function get(string $uri, callable $callback)
    {
        $this->routes['GET'][$uri] = $callback;
    }

    public function post(string $uri, callable $callback)
    {
        $this->routes['POST'][$uri] = $callback;
    }

    public function resolve(string $method, string $uri)
    {
        $callback = $this->routes[$method][$uri];

        if (is_array($callback)) {
            return call_user_func([$callback[0], $callback[1]]);
        }

        return $callback();
    }
}

