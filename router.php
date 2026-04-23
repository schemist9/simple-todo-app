<?php

class Router
{
    private $routes = [];
    
    public function __construct()
    {
        $this->routes = [];
    }

    public function get(string $uri, callable|array $callback)
    {
        $this->routes[$uri]['GET'] = $callback;
    }

    public function post(string $uri, callable|array $callback)
    {
        $this->routes[$uri]['POST'] = $callback;
    }

    public function delete(string $uri, callable|array $callback)
    {
        $this->routes[$uri]['delete'] = $callback;
    }

    public function resolve(string $method, string $uri)
    {
        if (!isset($this->routes[$uri])) {
            return null;
        }

        $callback = $this->routes[$uri][$method];

        if (!$callback) {
            return;
        }

        if (is_array($callback)) {
            $instance = new $callback[0];
            return call_user_func([$instance, $callback[1]]);
        }

        return $callback();
    }
}

