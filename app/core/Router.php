<?php

class Router
{
    private $routes = [];

    public function get(string $path, $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, $handler): void
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $path): void
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches);
                $handler = $route['handler'];
                if (is_array($handler)) {
                    $class = $handler[0];
                    $methodName = $handler[1];
                    $controller = new $class();
                    call_user_func_array([$controller, $methodName], $matches);
                    return;
                }
                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                    return;
                }
            }
        }
        http_response_code(404);
        echo '404 Not Found';
    }
}
