<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $action = $this->routes[$method][$path] ?? null;

        if (!$action) {
            http_response_code(404);
            echo 'Página no encontrada';
            return;
        }

        [$controller, $handler] = $action;
        (new $controller())->$handler();
    }
}
